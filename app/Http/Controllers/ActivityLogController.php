<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the activity logs.
     * 
     * This method fetches activity logs from the activity_log table (managed by Spatie)
     * and supports filtering by action, model_type, and date range.
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Start building the query with eager loading for better performance
        $query = Activity::with(['causer', 'subject'])
            ->orderBy('created_at', 'desc');

        // Filter by action (event) if provided
        // Spatie uses 'event' column for created/updated/deleted
        if ($request->filled('action')) {
            $query->where('event', $request->action);
        }

        // Filter by model type (subject_type) if provided
        // Spatie stores the model class name in 'subject_type' column
        if ($request->filled('model_type')) {
            $query->where('subject_type', $request->model_type);
        }

        // Filter by date range if provided
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Paginate results for better performance
        $logs = $query->paginate(50)->withQueryString();

        // Get unique events (actions) for filter dropdown
        // Spatie uses 'event' column: created, updated, deleted
        $actions = Activity::distinct()
            ->whereNotNull('event')
            ->pluck('event')
            ->sort()
            ->values();

        // Get unique model types (subject_type) for filter dropdown
        $modelTypes = Activity::distinct()
            ->whereNotNull('subject_type')
            ->pluck('subject_type')
            ->sort()
            ->values();

        return view('activity_logs.index', compact('logs', 'actions', 'modelTypes'));
    }
}
