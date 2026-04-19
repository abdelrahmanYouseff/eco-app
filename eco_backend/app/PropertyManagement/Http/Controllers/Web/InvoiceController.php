<?php

namespace App\PropertyManagement\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\PropertyManagement\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with(['contract', 'client', 'rentPayment']);

        // Filter by status if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by contract if provided
        if ($request->filled('contract_id')) {
            $query->where('contract_id', $request->contract_id);
        }

        // Search by invoice number
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('invoice_number', 'like', "%{$search}%");
        }

        $invoices = $query->orderBy('invoice_date', 'desc')->paginate(15);

        return view('property_management.invoices.index', compact('invoices'));
    }

    public function show($id)
    {
        $invoice = Invoice::with(['contract.unit.building', 'client', 'rentPayment'])->findOrFail($id);
        
        return view('property_management.invoices.show', compact('invoice'));
    }
}


