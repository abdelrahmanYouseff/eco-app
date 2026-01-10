<?php

namespace App\PropertyManagement\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\PropertyManagement\Models\Client;
use App\PropertyManagement\Models\Invoice;
use App\PropertyManagement\Models\ReceiptVoucher;
use App\PropertyManagement\Models\RentPayment;
use App\PropertyManagement\Services\Tenants\TenantService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TenantController extends Controller
{
    public function __construct(
        private TenantService $tenantService
    ) {}

    public function index(Request $request)
    {
        $query = $request->input('search');
        $tenants = $query 
            ? $this->tenantService->searchTenants($query)
            : Client::with('contracts')->paginate(15);
        
        return view('property_management.tenants.index', compact('tenants', 'query'));
    }

    public function create(Request $request)
    {
        $returnTo = $request->input('return_to');
        $contractData = $request->input('contract_data');
        
        return view('property_management.tenants.create', compact('returnTo', 'contractData'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'client_type' => 'required|in:فرد,شركة',
            'id_number_or_cr' => 'required|string|unique:clients,id_number_or_cr',
            'id_type' => 'nullable|string',
            'nationality' => 'nullable|string',
            'email' => 'nullable|email|unique:clients,email',
            'mobile' => 'required|string|unique:clients,mobile',
            'national_address' => 'nullable|string',
        ]);

        try {
            $tenant = $this->tenantService->createTenant($validated);
            
            // Check if we should return to contract creation page
            $returnTo = $request->input('return_to');
            if ($returnTo === 'contract') {
                return redirect()->route('property-management.contracts.create')
                    ->with('success', 'تم إضافة العميل بنجاح')
                    ->with('new_client_id', $tenant->id);
            }
            
            return redirect()->route('property-management.tenants.index')
                ->with('success', 'Tenant created successfully');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $tenant = Client::with(['contracts.unit.building', 'contracts.rentPayments'])->findOrFail($id);
        $statement = $this->tenantService->getAccountStatement($tenant);
        
        return view('property_management.tenants.show', compact('tenant', 'statement'));
    }

    public function destroy($id)
    {
        try {
            $tenant = Client::findOrFail($id);
            
            // Check if tenant has contracts
            if ($tenant->contracts()->count() > 0) {
                return redirect()->route('property-management.tenants.index')
                    ->with('error', 'لا يمكن حذف المستأجر لأنه مرتبط بعقود');
            }
            
            $tenant->delete();
            return redirect()->route('property-management.tenants.index')
                ->with('success', 'تم حذف المستأجر بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('property-management.tenants.index')
                ->with('error', 'حدث خطأ أثناء حذف المستأجر: ' . $e->getMessage());
        }
    }

    /**
     * Display customer account statements page
     */
    public function customerAccountStatements(Request $request)
    {
        $customerId = $request->input('customer_id');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        // Get all customers for dropdown
        $customers = Client::orderBy('name')->get();

        $transactions = [];
        $selectedCustomer = null;

        if ($customerId) {
            $selectedCustomer = Client::findOrFail($customerId);

            // Get ALL rent payments (paid and unpaid) for this customer, ordered by due_date
            $allRentPayments = RentPayment::whereHas('contract', function($q) use ($customerId) {
                    $q->where('client_id', $customerId);
                })
                ->with(['contract'])
                ->when($fromDate, function($q) use ($fromDate) {
                    return $q->where('due_date', '>=', Carbon::parse($fromDate));
                })
                ->when($toDate, function($q) use ($toDate) {
                    return $q->where('due_date', '<=', Carbon::parse($toDate));
                })
                ->orderBy('due_date', 'asc')
                ->get();

            // Calculate total amount due (sum of all rent payments)
            $totalAmountDue = $allRentPayments->sum('total_value');
            
            // Get earliest payment date for the initial transaction
            $earliestDate = $allRentPayments->min('issued_date') ?? $allRentPayments->min('due_date');
            $earliestDueDate = $allRentPayments->min('due_date');

            // Get all receipt vouchers for this customer, ordered by date
            $allReceiptVouchers = ReceiptVoucher::where('client_id', $customerId)
                ->with(['contract', 'rentPayment'])
                ->when($fromDate, function($q) use ($fromDate) {
                    return $q->where('receipt_date', '>=', Carbon::parse($fromDate));
                })
                ->when($toDate, function($q) use ($toDate) {
                    return $q->where('receipt_date', '<=', Carbon::parse($toDate));
                })
                ->orderBy('receipt_date', 'asc')
                ->get();
            
            // Create a map of rent payment IDs to due dates for quick lookup
            $rentPaymentDueDates = [];
            foreach ($allRentPayments as $payment) {
                $rentPaymentDueDates[$payment->id] = $payment->due_date;
            }

            // Calculate paid amount for each rent payment
            $rentPaymentPaidMap = [];
            foreach ($allReceiptVouchers as $receipt) {
                if ($receipt->rent_payment_id) {
                    if (!isset($rentPaymentPaidMap[$receipt->rent_payment_id])) {
                        $rentPaymentPaidMap[$receipt->rent_payment_id] = 0;
                    }
                    $rentPaymentPaidMap[$receipt->rent_payment_id] += $receipt->amount;
                }
            }

            // Build transactions
            $transactions = [];
            $transactionNumber = 1;
            $runningBalance = $totalAmountDue;

            // First row: Total amount due (المدين)
            if ($totalAmountDue > 0) {
                $transactions[] = [
                    'operation_number' => $transactionNumber++,
                    'reference_number' => 'إجمالي المستحقات',
                    'date' => $earliestDate ?? now(),
                    'due_date' => $earliestDueDate,
                    'description' => 'إجمالي المستحقات على العميل - ' . count($allRentPayments) . ' دفعة',
                    'debit' => $totalAmountDue,
                    'credit' => 0,
                    'balance' => $runningBalance,
                    'type' => 'total_due',
                ];
            }

            // Add all receipt vouchers as credits (الدائن)
            foreach ($allReceiptVouchers as $receipt) {
                $runningBalance -= $receipt->amount;
                
                // Get due date from related rent payment if available
                $dueDate = null;
                if ($receipt->rent_payment_id && isset($rentPaymentDueDates[$receipt->rent_payment_id])) {
                    $dueDate = $rentPaymentDueDates[$receipt->rent_payment_id];
                } elseif ($receipt->rentPayment) {
                    $dueDate = $receipt->rentPayment->due_date;
                }

                $transactions[] = [
                    'operation_number' => $transactionNumber++,
                    'reference_number' => $receipt->receipt_number . ($receipt->reference_number ? ' - ' . $receipt->reference_number : ''),
                    'date' => $receipt->receipt_date,
                    'due_date' => $dueDate,
                    'description' => 'سند قبض - ' . ($receipt->contract ? 'عقد رقم: ' . $receipt->contract->contract_number : ''),
                    'debit' => 0,
                    'credit' => $receipt->amount,
                    'balance' => $runningBalance,
                    'type' => 'receipt',
                    'id' => $receipt->id,
                ];
            }

            // Add unpaid rent payments (المتبقي المستحق) - show as detail without changing balance
            // These are already included in the total, so we just show them for reference
            $unpaidPayments = [];
            foreach ($allRentPayments as $payment) {
                $paidAmount = $rentPaymentPaidMap[$payment->id] ?? 0;
                $remainingAmount = $payment->total_value - $paidAmount;

                // Only show if there's a remaining amount (unpaid or partially paid)
                if ($remainingAmount > 0) {
                    $unpaidPayments[] = [
                        'payment' => $payment,
                        'remaining' => $remainingAmount,
                        'paid' => $paidAmount,
                    ];
                }
            }

            // Sort unpaid payments by due date
            usort($unpaidPayments, function($a, $b) {
                return $a['payment']->due_date->timestamp <=> $b['payment']->due_date->timestamp;
            });

            // Add unpaid payments as detail rows (for information, balance stays the same)
            foreach ($unpaidPayments as $unpaid) {
                $payment = $unpaid['payment'];
                $transactions[] = [
                    'operation_number' => $transactionNumber++,
                    'reference_number' => 'دفعة إيجار #' . $payment->id . ' (متبقي)',
                    'date' => $payment->issued_date ?? $payment->due_date,
                    'due_date' => $payment->due_date,
                    'description' => 'قسط إيجار مستحق (متبقي) - ' . ($payment->contract ? 'عقد رقم: ' . $payment->contract->contract_number : '') . ($unpaid['paid'] > 0 ? ' - مدفوع جزئياً: ' . number_format($unpaid['paid'], 2) . ' / ' . number_format($payment->total_value, 2) : ''),
                    'debit' => $unpaid['remaining'],
                    'credit' => 0,
                    'balance' => $runningBalance, // Balance stays the same (already included in total)
                    'type' => 'unpaid_rent',
                    'id' => $payment->id,
                ];
            }
        }

        return view('property_management.tenants.customer_account_statements', [
            'customers' => $customers,
            'selectedCustomer' => $selectedCustomer,
            'transactions' => $transactions,
            'customerId' => $customerId,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
        ]);
    }
}

