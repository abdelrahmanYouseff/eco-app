<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class WebhookController extends Controller
{
    /**
     * استقبال الطلبات من webhook وعرضها
     */
    public function receive(Request $request)
    {
        // تسجيل الطلب في السجل
        $logData = [
            'timestamp' => now()->toDateTimeString(),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'headers' => $request->headers->all(),
            'body' => $request->all(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ];

        // حفظ الطلب في ملف JSON
        $this->saveRequest($logData);

        // تسجيل في Laravel log
        Log::info('Webhook request received', $logData);

        return response()->json([
            'status' => 'success',
            'message' => 'Request received and logged successfully',
            'timestamp' => now()->toDateTimeString()
        ], 200);
    }

    /**
     * عرض جميع الطلبات المحفوظة
     */
    public function show()
    {
        $requests = $this->getAllRequests();

        return view('webhook.requests', [
            'requests' => $requests
        ]);
    }

    /**
     * حفظ الطلب في ملف JSON
     */
    private function saveRequest($data)
    {
        $filename = 'webhook_requests.json';
        $filepath = storage_path('app/' . $filename);

        $requests = [];

        if (Storage::exists($filename)) {
            $requests = json_decode(Storage::get($filename), true) ?? [];
        }

        // إضافة الطلب الجديد
        $requests[] = $data;

        // حفظ الملف
        Storage::put($filename, json_encode($requests, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    /**
     * جلب جميع الطلبات المحفوظة
     */
    private function getAllRequests()
    {
        $filename = 'webhook_requests.json';

        if (!Storage::exists($filename)) {
            return [];
        }

        $requests = json_decode(Storage::get($filename), true) ?? [];

        // ترتيب الطلبات من الأحدث إلى الأقدم
        return array_reverse($requests);
    }

    /**
     * حذف جميع الطلبات المحفوظة
     */
    public function clear()
    {
        $filename = 'webhook_requests.json';

        if (Storage::exists($filename)) {
            Storage::delete($filename);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'All requests cleared successfully'
        ]);
    }
}
