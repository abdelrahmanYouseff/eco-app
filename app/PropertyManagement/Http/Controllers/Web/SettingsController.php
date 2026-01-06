<?php

namespace App\PropertyManagement\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    public function index()
    {
        // Get settings from cache or config
        $settings = [
            'vat_percentage' => Cache::get('settings.vat_percentage', config('property_management.vat_percentage', 15)),
            'notification_days_before' => Cache::get('settings.notification_days_before', config('property_management.notification_days_before', 30)),
            'currency' => Cache::get('settings.currency', config('property_management.currency', 'SAR')),
            'company_name' => Cache::get('settings.company_name', config('property_management.company_name', '')),
            'company_address' => Cache::get('settings.company_address', config('property_management.company_address', '')),
            'company_phone' => Cache::get('settings.company_phone', config('property_management.company_phone', '')),
            'company_email' => Cache::get('settings.company_email', config('property_management.company_email', '')),
            'invoice_prefix' => Cache::get('settings.invoice_prefix', config('property_management.invoice_prefix', 'INV')),
            'receipt_prefix' => Cache::get('settings.receipt_prefix', config('property_management.receipt_prefix', 'REC')),
        ];

        return view('property_management.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'vat_percentage' => 'required|numeric|min:0|max:100',
            'notification_days_before' => 'required|integer|min:1|max:365',
            'currency' => 'required|string|max:10',
            'company_name' => 'nullable|string|max:255',
            'company_address' => 'nullable|string',
            'company_phone' => 'nullable|string|max:20',
            'company_email' => 'nullable|email|max:255',
            'invoice_prefix' => 'required|string|max:10',
            'receipt_prefix' => 'required|string|max:10',
        ]);

        // Store settings in cache
        foreach ($validated as $key => $value) {
            Cache::forever("settings.{$key}", $value);
        }

        return redirect()->route('property-management.settings.index')
            ->with('success', 'تم حفظ الإعدادات بنجاح');
    }
}

