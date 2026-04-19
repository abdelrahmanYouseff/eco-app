<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    /**
     * Log an activity
     */
    protected function logActivity($action, $model = null, $description = null, $oldValues = null, $newValues = null)
    {
        try {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => $action,
                'model_type' => $model ? get_class($model) : null,
                'model_id' => $model ? $model->id : null,
                'description' => $description,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'old_values' => $oldValues,
                'new_values' => $newValues,
            ]);
        } catch (\Exception $e) {
            // Silently fail if activity_logs table doesn't exist
            // This allows the application to work even if migrations haven't been run
        }
    }
}
