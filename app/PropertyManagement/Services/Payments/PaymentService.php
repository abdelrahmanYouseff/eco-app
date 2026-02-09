<?php

namespace App\PropertyManagement\Services\Payments;

use App\PropertyManagement\Models\Contract;
use App\PropertyManagement\Models\Invoice;
use App\PropertyManagement\Models\ReceiptVoucher;
use App\PropertyManagement\Models\RentPayment;
use App\PropertyManagement\Models\Transaction;
use App\PropertyManagement\Repositories\Payments\Interfaces\PaymentRepositoryInterface;
use App\PropertyManagement\Services\Notifications\NotificationService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public function __construct(
        private PaymentRepositoryInterface $paymentRepository,
        private NotificationService $notificationService
    ) {}

    /**
     * Generate payment schedule for a contract
     */
    public function generatePaymentSchedule(Contract $contract): Collection
    {
        $payments = collect();
        $startDate = Carbon::parse($contract->start_date);
        $endDate = Carbon::parse($contract->end_date);
        $rentCycle = $contract->rent_cycle; // in months
        
        // Calculate payment values based on rent cycle
        // If rent_cycle is 6 months, each payment covers 6 months worth of rent
        // If rent_cycle is 12 months, each payment covers 12 months worth of rent
        $cycleRent = ($contract->annual_rent * $rentCycle) / 12;
        
        // الخدمات العامة تُضاف بالكامل مع كل دفعة استحقاق
        // Services are added in full with each payment due date
        $cycleServices = $contract->general_services_amount; // Full amount, not divided
        
        // المبالغ الثابتة تُقسم على عدد دورات الإيجار في السنة
        // Fixed amounts are divided by the number of payment cycles per year
        // Example: if fixed_amounts = 3000 and rent_cycle = 6 months (2 cycles per year)
        // then cycleFixedAmounts = 3000 / 2 = 1500 per payment
        $cyclesPerYear = 12 / $rentCycle;
        $cycleFixedAmounts = $contract->fixed_amounts / $cyclesPerYear;
        
        // VAT is calculated based on rent cycle (proportional to rent)
        $cycleVat = ($contract->vat_amount * $rentCycle) / 12;

        // First payment due date is start date + 10 days
        // Example: if start_date is 2024-01-17, first payment due_date will be 2024-01-27
        // Subsequent payments: previous due date + rent_cycle months
        $currentDate = $startDate->copy()->addDays(10);

        while ($currentDate->lte($endDate)) {
            $dueDate = $currentDate->copy();
            $issuedDate = $currentDate->copy()->subDays(7); // Issue 7 days before due

            $payment = $this->paymentRepository->createRentPayment([
                'contract_id' => $contract->id,
                'due_date' => $dueDate,
                'issued_date' => $issuedDate,
                'rent_value' => $cycleRent,
                'services_value' => $cycleServices,
                'vat_value' => $cycleVat,
                'fixed_amounts' => $cycleFixedAmounts,
                'total_value' => $cycleRent + $cycleServices + $cycleVat + $cycleFixedAmounts,
                'status' => 'unpaid',
            ]);

            $payments->push($payment);
            
            // Create notification if payment is due in 30 days or less
            $daysUntilDue = Carbon::now()->diffInDays($dueDate);
            if ($daysUntilDue <= 30 && $daysUntilDue >= 0) {
                $this->createPaymentDueNotification($payment, $daysUntilDue);
            }
            
            // Next payment due date: current due date + rent_cycle months
            $currentDate->addMonths($rentCycle);
        }

        return $payments;
    }

    /**
     * Record payment
     */
    public function recordPayment(
        RentPayment $rentPayment,
        float $amount,
        Carbon $paymentDate = null,
        string $description = null
    ): Transaction {
        $paymentDate = $paymentDate ?? now();

        // Update rent payment status
        $paidAmount = $rentPayment->contract->transactions()
            ->where('type', 'payment')
            ->whereHas('contract', function ($q) use ($rentPayment) {
                $q->where('id', $rentPayment->contract_id);
            })
            ->sum('amount');

        $totalPaid = $paidAmount + $amount;
        $status = 'paid';

        if ($totalPaid < $rentPayment->total_value) {
            $status = 'partially_paid';
        }

        $rentPayment->update([
            'status' => $status,
            'payment_date' => $paymentDate,
        ]);

        // Create transaction
        return $this->paymentRepository->createTransaction([
            'client_id' => $rentPayment->contract->client_id,
            'contract_id' => $rentPayment->contract_id,
            'amount' => $amount,
            'type' => 'payment',
            'date' => $paymentDate,
            'description' => $description ?? "Payment for contract {$rentPayment->contract->contract_number}",
        ]);
    }

    /**
     * Cancel future payments
     */
    public function cancelFuturePayments(Contract $contract, Carbon $terminationDate): void
    {
        RentPayment::where('contract_id', $contract->id)
            ->where('due_date', '>', $terminationDate)
            ->where('status', 'unpaid')
            ->delete();
    }

    /**
     * Get contract payments
     */
    public function getContractPayments(int $contractId): Collection
    {
        return $this->paymentRepository->getRentPaymentsByContract($contractId);
    }

    /**
     * Get pending payments
     */
    public function getPendingPayments(): Collection
    {
        return $this->paymentRepository->getPendingRentPayments();
    }

    /**
     * Get overdue payments
     */
    public function getOverduePayments(): Collection
    {
        return $this->paymentRepository->getOverdueRentPayments();
    }

    /**
     * Create adjustment transaction
     */
    public function createAdjustment(
        Contract $contract,
        float $amount,
        string $description
    ): Transaction {
        return $this->paymentRepository->createTransaction([
            'client_id' => $contract->client_id,
            'contract_id' => $contract->id,
            'amount' => $amount,
            'type' => 'adjustment',
            'date' => now(),
            'description' => $description,
        ]);
    }

    /**
     * Create refund transaction
     */
    public function createRefund(
        Contract $contract,
        float $amount,
        string $description
    ): Transaction {
        return $this->paymentRepository->createTransaction([
            'client_id' => $contract->client_id,
            'contract_id' => $contract->id,
            'amount' => $amount,
            'type' => 'refund',
            'date' => now(),
            'description' => $description,
        ]);
    }

    /**
     * Get client balance
     */
    public function getClientBalance(int $clientId): float
    {
        return $this->paymentRepository->getClientBalance($clientId);
    }

    /**
     * Mark payment as paid and create invoice and receipt voucher
     */
    public function markPaymentAsPaid(RentPayment $rentPayment, ?Carbon $paymentDate = null): array
    {
        $paymentDate = $paymentDate ?? now();

        return DB::transaction(function () use ($rentPayment, $paymentDate) {
            // Update payment status
            $rentPayment->update([
                'status' => 'paid',
                'payment_date' => $paymentDate,
            ]);

            $contract = $rentPayment->contract;
            $client = $contract->client;

            // Generate invoice number
            $invoiceNumber = $this->generateInvoiceNumber();
            
            // Create invoice
            $invoice = Invoice::create([
                'invoice_number' => $invoiceNumber,
                'contract_id' => $contract->id,
                'rent_payment_id' => $rentPayment->id,
                'client_id' => $client->id,
                'invoice_date' => $paymentDate,
                'due_date' => $rentPayment->due_date,
                'subtotal' => $rentPayment->rent_value + $rentPayment->services_value + ($rentPayment->fixed_amounts ?? 0),
                'vat_amount' => $rentPayment->vat_value,
                'total_amount' => $rentPayment->total_value,
                'status' => 'paid',
                'notes' => "فاتورة للدفعة المستحقة بتاريخ: {$rentPayment->due_date->format('Y-m-d')}",
            ]);

            // Generate receipt voucher number
            $receiptNumber = $this->generateReceiptNumber();
            
            // Create receipt voucher
            $receiptVoucher = ReceiptVoucher::create([
                'receipt_number' => $receiptNumber,
                'contract_id' => $contract->id,
                'client_id' => $client->id,
                'rent_payment_id' => $rentPayment->id,
                'receipt_date' => $paymentDate,
                'amount' => $rentPayment->total_value,
                'payment_method' => 'cash', // Default, can be changed later
                'notes' => "سند قبض للدفعة المستحقة بتاريخ: {$rentPayment->due_date->format('Y-m-d')}",
            ]);

            // Create transaction
            $this->paymentRepository->createTransaction([
                'client_id' => $client->id,
                'contract_id' => $contract->id,
                'amount' => $rentPayment->total_value,
                'type' => 'payment',
                'date' => $paymentDate,
                'description' => "دفعة للعقد {$contract->contract_number} - فاتورة {$invoiceNumber}",
            ]);

            return [
                'payment' => $rentPayment->fresh(),
                'invoice' => $invoice,
                'receipt_voucher' => $receiptVoucher,
            ];
        });
    }

    /**
     * Generate unique invoice number
     */
    private function generateInvoiceNumber(): string
    {
        $year = now()->format('Y');
        $lastInvoice = Invoice::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastInvoice) {
            $lastNumber = (int) substr($lastInvoice->invoice_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'INV-' . $year . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Generate unique receipt voucher number
     */
    private function generateReceiptNumber(): string
    {
        $year = now()->format('Y');
        $lastReceipt = ReceiptVoucher::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastReceipt) {
            $lastNumber = (int) substr($lastReceipt->receipt_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'REC-' . $year . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Create payment due notification for a specific payment
     */
    private function createPaymentDueNotification(RentPayment $payment, int $daysUntilDue): void
    {
        $contract = $payment->contract;
        $unit = $contract->unit;
        $building = $unit->building;
        $dueDateFormatted = Carbon::parse($payment->due_date)->format('Y-m-d');

        // Check if notification already exists
        $existingNotification = \App\PropertyManagement\Models\Notification::where('rent_payment_id', $payment->id)
            ->where('type', 'payment_due')
            ->first();

        if (!$existingNotification) {
            \App\PropertyManagement\Models\Notification::create([
                'user_id' => null,
                'type' => 'payment_due',
                'title' => 'استحقاق دفعة قادم',
                'message' => "رقم المكتب: {$unit->unit_number} - اسم المبنى: {$building->name} - القيمة المستحقة: " . number_format($payment->total_value, 2) . " ريال - تاريخ الاستحقاق: {$dueDateFormatted}",
                'rent_payment_id' => $payment->id,
                'contract_id' => $contract->id,
                'is_read' => false,
            ]);
        }
    }
}

