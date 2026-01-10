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
}



