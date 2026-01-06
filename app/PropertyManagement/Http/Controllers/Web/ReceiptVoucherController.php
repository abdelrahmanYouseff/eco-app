<?php

namespace App\PropertyManagement\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\PropertyManagement\Models\ReceiptVoucher;
use Illuminate\Http\Request;

class ReceiptVoucherController extends Controller
{
    public function index(Request $request)
    {
        $query = ReceiptVoucher::with(['contract', 'client', 'rentPayment']);

        // Filter by payment method if provided
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by contract if provided
        if ($request->filled('contract_id')) {
            $query->where('contract_id', $request->contract_id);
        }

        // Search by receipt number
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('receipt_number', 'like', "%{$search}%");
        }

        $receiptVouchers = $query->orderBy('receipt_date', 'desc')->paginate(15);

        return view('property_management.receipt_vouchers.index', compact('receiptVouchers'));
    }

    public function show($id)
    {
        $receiptVoucher = ReceiptVoucher::with(['contract.unit.building', 'client', 'rentPayment'])->findOrFail($id);
        
        return view('property_management.receipt_vouchers.show', compact('receiptVoucher'));
    }
}


