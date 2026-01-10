<?php

namespace App\PropertyManagement\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\PropertyManagement\Models\RentPayment;
use App\PropertyManagement\Models\Invoice;
use App\PropertyManagement\Models\ReceiptVoucher;
use App\PropertyManagement\Models\Contract;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AccountingController extends Controller
{
    public function index(Request $request)
    {
        // Get date range from request or use default (current month)
        $fromDate = $request->input('from_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $toDate = $request->input('to_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Total Revenue (all paid payments)
        $totalRevenue = RentPayment::where('status', 'paid')
            ->whereBetween('payment_date', [$fromDate, $toDate])
            ->sum('total_value');

        // Total Pending (unpaid payments)
        $totalPending = RentPayment::where('status', '!=', 'paid')
            ->sum('total_value');

        // Total Overdue (unpaid payments past due date)
        $totalOverdue = RentPayment::where('status', '!=', 'paid')
            ->where('due_date', '<', Carbon::now())
            ->sum('total_value');

        // Total Invoices
        $totalInvoices = Invoice::whereBetween('invoice_date', [$fromDate, $toDate])
            ->sum('total_amount');

        // Total Receipt Vouchers
        $totalReceipts = ReceiptVoucher::whereBetween('receipt_date', [$fromDate, $toDate])
            ->sum('amount');

        // Overdue Payments Count
        $overduePaymentsCount = RentPayment::where('status', '!=', 'paid')
            ->where('due_date', '<', Carbon::now())
            ->count();

        // Recent Payments (last 10)
        $recentPayments = RentPayment::with(['contract.client', 'contract.unit'])
            ->where('status', 'paid')
            ->whereBetween('payment_date', [$fromDate, $toDate])
            ->orderBy('payment_date', 'desc')
            ->limit(10)
            ->get();

        // Overdue Payments
        $overduePayments = RentPayment::with(['contract.client', 'contract.unit'])
            ->where('status', '!=', 'paid')
            ->where('due_date', '<', Carbon::now())
            ->orderBy('due_date', 'asc')
            ->limit(10)
            ->get();

        // Statistics by month (last 6 months)
        $monthlyStats = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthStart = Carbon::now()->subMonths($i)->startOfMonth();
            $monthEnd = Carbon::now()->subMonths($i)->endOfMonth();
            
            $monthlyStats[] = [
                'month' => $monthStart->format('Y-m'),
                'month_name' => $monthStart->format('M Y'),
                'revenue' => RentPayment::where('status', 'paid')
                    ->whereBetween('payment_date', [$monthStart, $monthEnd])
                    ->sum('total_value'),
                'count' => RentPayment::where('status', 'paid')
                    ->whereBetween('payment_date', [$monthStart, $monthEnd])
                    ->count(),
            ];
        }

        return view('property_management.accounting.index', compact(
            'totalRevenue',
            'totalPending',
            'totalOverdue',
            'totalInvoices',
            'totalReceipts',
            'overduePaymentsCount',
            'recentPayments',
            'overduePayments',
            'monthlyStats',
            'fromDate',
            'toDate'
        ));
    }

    /**
     * Show detailed revenues page
     */
    public function revenues(Request $request)
    {
        // Get date range from request or use default (current month)
        $fromDate = $request->input('from_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $toDate = $request->input('to_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Get all paid rent payments (revenues) with contract and client info
        $revenuesQuery = RentPayment::with(['contract.client', 'contract.unit', 'contract.building'])
            ->where('status', 'paid')
            ->whereNotNull('payment_date')
            ->whereBetween('payment_date', [$fromDate, $toDate]);

        // Apply search if provided
        $search = $request->input('search');
        if ($search) {
            $revenuesQuery->where(function($q) use ($search) {
                $q->whereHas('contract.client', function($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                          ->orWhere('mobile', 'like', "%{$search}%")
                          ->orWhere('id_number_or_cr', 'like', "%{$search}%");
                })->orWhereHas('contract', function($query) use ($search) {
                    $query->where('contract_number', 'like', "%{$search}%");
                });
            });
        }

        $revenues = $revenuesQuery->orderBy('payment_date', 'desc')
            ->paginate(20);

        // Calculate total revenue for the period
        $totalRevenue = RentPayment::where('status', 'paid')
            ->whereNotNull('payment_date')
            ->whereBetween('payment_date', [$fromDate, $toDate])
            ->sum('total_value');

        return view('property_management.accounting.revenues', compact(
            'revenues',
            'totalRevenue',
            'fromDate',
            'toDate',
            'search'
        ));
    }

    /**
     * Export revenues to Excel (CSV format compatible with Excel)
     */
    public function exportRevenues(Request $request)
    {
        // Get date range from request or use default (current month)
        $fromDate = $request->input('from_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $toDate = $request->input('to_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Get all paid rent payments (revenues) with contract and client info
        $revenuesQuery = RentPayment::with(['contract.client', 'contract.unit', 'contract.building'])
            ->where('status', 'paid')
            ->whereNotNull('payment_date')
            ->whereBetween('payment_date', [$fromDate, $toDate]);

        // Apply search if provided
        $search = $request->input('search');
        if ($search) {
            $revenuesQuery->where(function($q) use ($search) {
                $q->whereHas('contract.client', function($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                          ->orWhere('mobile', 'like', "%{$search}%")
                          ->orWhere('id_number_or_cr', 'like', "%{$search}%");
                })->orWhereHas('contract', function($query) use ($search) {
                    $query->where('contract_number', 'like', "%{$search}%");
                });
            });
        }

        $revenues = $revenuesQuery->orderBy('payment_date', 'desc')->get();

        // Create Excel filename
        $filename = 'revenues_' . $fromDate . '_' . $toDate . '.csv';
        
        // Create CSV content with UTF-8 BOM for Excel compatibility
        $output = "\xEF\xBB\xBF"; // UTF-8 BOM for Excel
        
        // Headers row
        $output .= '"رقم العقد","اسم العميل","المبنى","الوحدة","تاريخ الاستحقاق","تاريخ الدفع","الإيجار","الخدمات","الضريبة","المبلغ الإجمالي"' . "\n";

        // Data rows
        foreach ($revenues as $payment) {
            $output .= '"' . ($payment->contract->contract_number ?? 'غير متوفر') . '",';
            $output .= '"' . ($payment->contract->client->name ?? 'غير متوفر') . '",';
            $output .= '"' . ($payment->contract->building->name ?? 'غير متوفر') . '",';
            $output .= '"' . ($payment->contract->unit->unit_number ?? 'غير متوفر') . '",';
            $output .= '"' . ($payment->due_date ? $payment->due_date->format('Y-m-d') : 'غير محدد') . '",';
            $output .= '"' . ($payment->payment_date ? $payment->payment_date->format('Y-m-d') : 'غير محدد') . '",';
            $output .= '"' . number_format($payment->rent_value, 2) . '",';
            $output .= '"' . number_format($payment->services_value ?? 0, 2) . '",';
            $output .= '"' . number_format($payment->vat_value ?? 0, 2) . '",';
            $output .= '"' . number_format($payment->total_value, 2) . '"';
            $output .= "\n";
        }

        // Total row
        $output .= '"","","","","","","الإجمالي:",';
        $output .= '"' . number_format($revenues->sum('services_value'), 2) . '",';
        $output .= '"' . number_format($revenues->sum('vat_value'), 2) . '",';
        $output .= '"' . number_format($revenues->sum('total_value'), 2) . '"';
        $output .= "\n";

        return response($output, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Content-Encoding' => 'UTF-8',
        ]);
    }

    /**
     * Show pending payments page
     */
    public function pending(Request $request)
    {
        // Get date range from request or use default (current month)
        $fromDate = $request->input('from_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $toDate = $request->input('to_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Get all unpaid payments with contract and client info
        $pendingQuery = RentPayment::with(['contract.client', 'contract.unit', 'contract.building'])
            ->where('status', '!=', 'paid')
            ->where(function($q) use ($fromDate, $toDate) {
                $q->whereBetween('due_date', [$fromDate, $toDate])
                  ->orWhereNull('due_date');
            });

        // Apply search if provided
        $search = $request->input('search');
        if ($search) {
            $pendingQuery->where(function($q) use ($search) {
                $q->whereHas('contract.client', function($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                          ->orWhere('mobile', 'like', "%{$search}%")
                          ->orWhere('id_number_or_cr', 'like', "%{$search}%");
                })->orWhereHas('contract', function($query) use ($search) {
                    $query->where('contract_number', 'like', "%{$search}%");
                });
            });
        }

        $pendingPayments = $pendingQuery->orderBy('due_date', 'asc')
            ->paginate(20);

        // Calculate total pending for the period
        $totalPending = RentPayment::where('status', '!=', 'paid')
            ->where(function($q) use ($fromDate, $toDate) {
                $q->whereBetween('due_date', [$fromDate, $toDate])
                  ->orWhereNull('due_date');
            })
            ->sum('total_value');

        // Count overdue payments (unpaid payments past due date)
        $overdueCount = RentPayment::where('status', '!=', 'paid')
            ->where('due_date', '<', Carbon::now())
            ->where(function($q) use ($fromDate, $toDate) {
                $q->whereBetween('due_date', [$fromDate, $toDate])
                  ->orWhereNull('due_date');
            })
            ->count();

        return view('property_management.accounting.pending', compact(
            'pendingPayments',
            'totalPending',
            'overdueCount',
            'fromDate',
            'toDate',
            'search'
        ));
    }

    /**
     * Export pending payments to Excel
     */
    public function exportPending(Request $request)
    {
        // Get date range from request or use default (current month)
        $fromDate = $request->input('from_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $toDate = $request->input('to_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Get all unpaid payments with contract and client info
        $pendingQuery = RentPayment::with(['contract.client', 'contract.unit', 'contract.building'])
            ->where('status', '!=', 'paid')
            ->where(function($q) use ($fromDate, $toDate) {
                $q->whereBetween('due_date', [$fromDate, $toDate])
                  ->orWhereNull('due_date');
            });

        // Apply search if provided
        $search = $request->input('search');
        if ($search) {
            $pendingQuery->where(function($q) use ($search) {
                $q->whereHas('contract.client', function($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                          ->orWhere('mobile', 'like', "%{$search}%")
                          ->orWhere('id_number_or_cr', 'like', "%{$search}%");
                })->orWhereHas('contract', function($query) use ($search) {
                    $query->where('contract_number', 'like', "%{$search}%");
                });
            });
        }

        $pendingPayments = $pendingQuery->orderBy('due_date', 'asc')->get();

        // Create Excel filename
        $filename = 'pending_payments_' . $fromDate . '_' . $toDate . '.csv';
        
        // Create CSV content with UTF-8 BOM for Excel compatibility
        $output = "\xEF\xBB\xBF"; // UTF-8 BOM for Excel
        
        // Headers row
        $output .= '"رقم العقد","اسم العميل","المبنى","الوحدة","تاريخ الاستحقاق","تاريخ الإصدار","الحالة","أيام التأخير","الإيجار","الخدمات","الضريبة","المبلغ الإجمالي"' . "\n";

        // Data rows
        foreach ($pendingPayments as $payment) {
            $daysOverdue = 0;
            $statusText = 'غير مدفوع';
            
            if ($payment->status === 'partially_paid') {
                $statusText = 'مدفوع جزئياً';
            } elseif ($payment->status === 'paid') {
                $statusText = 'مدفوع';
            }
            
            if ($payment->status !== 'paid' && $payment->due_date && $payment->due_date < now()) {
                $daysOverdue = now()->diffInDays($payment->due_date);
            }

            $output .= '"' . ($payment->contract->contract_number ?? 'غير متوفر') . '",';
            $output .= '"' . ($payment->contract->client->name ?? 'غير متوفر') . '",';
            $output .= '"' . ($payment->contract->building->name ?? 'غير متوفر') . '",';
            $output .= '"' . ($payment->contract->unit->unit_number ?? 'غير متوفر') . '",';
            $output .= '"' . ($payment->due_date ? $payment->due_date->format('Y-m-d') : 'غير محدد') . '",';
            $output .= '"' . ($payment->issued_date ? $payment->issued_date->format('Y-m-d') : 'غير محدد') . '",';
            $output .= '"' . $statusText . '",';
            $output .= '"' . ($daysOverdue > 0 ? $daysOverdue . ' يوم' : '-') . '",';
            $output .= '"' . number_format($payment->rent_value, 2) . '",';
            $output .= '"' . number_format($payment->services_value ?? 0, 2) . '",';
            $output .= '"' . number_format($payment->vat_value ?? 0, 2) . '",';
            $output .= '"' . number_format($payment->total_value, 2) . '"';
            $output .= "\n";
        }

        // Total row
        $output .= '"","","","","","","","","","","الإجمالي:",';
        $output .= '"' . number_format($pendingPayments->sum('total_value'), 2) . '"';
        $output .= "\n";

        return response($output, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Content-Encoding' => 'UTF-8',
        ]);
    }

    /**
     * Show invoices page
     */
    public function invoices(Request $request)
    {
        // Get date range from request or use default (current month)
        $fromDate = $request->input('from_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $toDate = $request->input('to_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Get all invoices with contract and client info
        $invoicesQuery = Invoice::with(['contract.client', 'contract.unit', 'contract.building', 'client'])
            ->whereBetween('invoice_date', [$fromDate, $toDate]);

        // Apply search if provided
        $search = $request->input('search');
        if ($search) {
            $invoicesQuery->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('client', function($query) use ($search) {
                      $query->where('name', 'like', "%{$search}%")
                            ->orWhere('mobile', 'like', "%{$search}%")
                            ->orWhere('id_number_or_cr', 'like', "%{$search}%");
                  })->orWhereHas('contract', function($query) use ($search) {
                      $query->where('contract_number', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status if provided
        if ($request->filled('status')) {
            $invoicesQuery->where('status', $request->status);
        }

        $invoices = $invoicesQuery->orderBy('invoice_date', 'desc')
            ->paginate(20);

        // Calculate total invoices for the period
        $totalInvoices = Invoice::whereBetween('invoice_date', [$fromDate, $toDate])
            ->sum('total_amount');

        // Count by status
        $statusCounts = [
            'draft' => Invoice::whereBetween('invoice_date', [$fromDate, $toDate])->where('status', 'draft')->count(),
            'sent' => Invoice::whereBetween('invoice_date', [$fromDate, $toDate])->where('status', 'sent')->count(),
            'paid' => Invoice::whereBetween('invoice_date', [$fromDate, $toDate])->where('status', 'paid')->count(),
            'cancelled' => Invoice::whereBetween('invoice_date', [$fromDate, $toDate])->where('status', 'cancelled')->count(),
        ];

        return view('property_management.accounting.invoices', compact(
            'invoices',
            'totalInvoices',
            'statusCounts',
            'fromDate',
            'toDate',
            'search'
        ));
    }

    /**
     * Export invoices to Excel
     */
    public function exportInvoices(Request $request)
    {
        // Get date range from request or use default (current month)
        $fromDate = $request->input('from_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $toDate = $request->input('to_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Get all invoices with contract and client info
        $invoicesQuery = Invoice::with(['contract.client', 'contract.unit', 'contract.building', 'client'])
            ->whereBetween('invoice_date', [$fromDate, $toDate]);

        // Apply search if provided
        $search = $request->input('search');
        if ($search) {
            $invoicesQuery->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('client', function($query) use ($search) {
                      $query->where('name', 'like', "%{$search}%")
                            ->orWhere('mobile', 'like', "%{$search}%")
                            ->orWhere('id_number_or_cr', 'like', "%{$search}%");
                  })->orWhereHas('contract', function($query) use ($search) {
                      $query->where('contract_number', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status if provided
        if ($request->filled('status')) {
            $invoicesQuery->where('status', $request->status);
        }

        $invoices = $invoicesQuery->orderBy('invoice_date', 'desc')->get();

        // Create Excel filename
        $filename = 'invoices_' . $fromDate . '_' . $toDate . '.csv';
        
        // Create CSV content with UTF-8 BOM for Excel compatibility
        $output = "\xEF\xBB\xBF"; // UTF-8 BOM for Excel
        
        // Headers row
        $output .= '"رقم الفاتورة","رقم العقد","اسم العميل","المبنى","الوحدة","تاريخ الفاتورة","تاريخ الاستحقاق","الحالة","المبلغ الفرعي","قيمة الضريبة","المبلغ الإجمالي"' . "\n";

        // Data rows
        foreach ($invoices as $invoice) {
            $statusText = '';
            switch ($invoice->status) {
                case 'draft':
                    $statusText = 'مسودة';
                    break;
                case 'sent':
                    $statusText = 'مرسلة';
                    break;
                case 'paid':
                    $statusText = 'مدفوعة';
                    break;
                case 'cancelled':
                    $statusText = 'ملغاة';
                    break;
                default:
                    $statusText = $invoice->status;
            }

            $output .= '"' . ($invoice->invoice_number ?? 'غير متوفر') . '",';
            $output .= '"' . ($invoice->contract->contract_number ?? 'غير متوفر') . '",';
            $output .= '"' . ($invoice->client->name ?? 'غير متوفر') . '",';
            $output .= '"' . ($invoice->contract->building->name ?? 'غير متوفر') . '",';
            $output .= '"' . ($invoice->contract->unit->unit_number ?? 'غير متوفر') . '",';
            $output .= '"' . ($invoice->invoice_date ? $invoice->invoice_date->format('Y-m-d') : 'غير محدد') . '",';
            $output .= '"' . ($invoice->due_date ? $invoice->due_date->format('Y-m-d') : 'غير محدد') . '",';
            $output .= '"' . $statusText . '",';
            $output .= '"' . number_format($invoice->subtotal, 2) . '",';
            $output .= '"' . number_format($invoice->vat_amount ?? 0, 2) . '",';
            $output .= '"' . number_format($invoice->total_amount, 2) . '"';
            $output .= "\n";
        }

        // Total row
        $output .= '"","","","","","","","","","الإجمالي:",';
        $output .= '"' . number_format($invoices->sum('total_amount'), 2) . '"';
        $output .= "\n";

        return response($output, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Content-Encoding' => 'UTF-8',
        ]);
    }
}



