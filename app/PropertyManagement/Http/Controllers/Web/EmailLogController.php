<?php

namespace App\PropertyManagement\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\EmailLog;
use Illuminate\Http\Request;

class EmailLogController extends Controller
{
    public function index(Request $request)
    {
        $query = EmailLog::with(['contract', 'client', 'rentPayment', 'sentByUser'])
            ->orderBy('created_at', 'desc');

        // Filter by status if provided
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by contract if provided
        if ($request->has('contract_id') && $request->contract_id !== '') {
            $query->where('contract_id', $request->contract_id);
        }

        // Filter by date range if provided
        if ($request->has('date_from') && $request->date_from !== '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to !== '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $emailLogs = $query->paginate(20);

        return view('property_management.email_logs.index', compact('emailLogs'));
    }
}
