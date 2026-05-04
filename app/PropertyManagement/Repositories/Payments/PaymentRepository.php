<?php

namespace App\PropertyManagement\Repositories\Payments;

use App\PropertyManagement\Models\RentPayment;
use App\PropertyManagement\Models\Transaction;
use App\PropertyManagement\Repositories\Payments\Interfaces\PaymentRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class PaymentRepository implements PaymentRepositoryInterface
{
    // Rent Payments
    public function findRentPayment(int $id): ?RentPayment
    {
        return RentPayment::with('contract')->find($id);
    }

    public function createRentPayment(array $data): RentPayment
    {
        return DB::transaction(function () use ($data) {
            return RentPayment::create($data);
        });
    }

    public function updateRentPayment(RentPayment $payment, array $data): RentPayment
    {
        return DB::transaction(function () use ($payment, $data) {
            $payment->update($data);
            return $payment->fresh();
        });
    }

    public function getRentPaymentsByContract(int $contractId): Collection
    {
        return RentPayment::where('contract_id', $contractId)
            ->orderBy('due_date', 'asc')
            ->get();
    }

    public function getPendingRentPayments(): Collection
    {
        return RentPayment::whereIn('status', ['unpaid', 'partially_paid'])
            ->with('contract')
            ->orderBy('due_date', 'asc')
            ->get();
    }

    public function getOverdueRentPayments(): Collection
    {
        return RentPayment::whereIn('status', ['unpaid', 'partially_paid'])
            ->where('due_date', '<', now()->toDateString())
            ->with('contract')
            ->get();
    }

    // Transactions
    public function findTransaction(int $id): ?Transaction
    {
        return Transaction::with(['client', 'contract'])->find($id);
    }

    public function createTransaction(array $data): Transaction
    {
        return DB::transaction(function () use ($data) {
            return Transaction::create($data);
        });
    }

    public function getTransactionsByContract(int $contractId): Collection
    {
        return Transaction::where('contract_id', $contractId)
            ->with('client')
            ->orderBy('date', 'desc')
            ->get();
    }

    public function getTransactionsByClient(int $clientId): Collection
    {
        return Transaction::where('client_id', $clientId)
            ->with('contract')
            ->orderBy('date', 'desc')
            ->get();
    }

    public function getClientBalance(int $clientId): float
    {
        $payments = Transaction::where('client_id', $clientId)
            ->where('type', 'payment')
            ->sum('amount');

        $adjustments = Transaction::where('client_id', $clientId)
            ->where('type', 'adjustment')
            ->sum('amount');

        $refunds = Transaction::where('client_id', $clientId)
            ->where('type', 'refund')
            ->sum('amount');

        return (float) ($payments + $adjustments - $refunds);
    }
}


