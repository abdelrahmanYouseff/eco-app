<?php

namespace App\PropertyManagement\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\EmailLog;
use App\PropertyManagement\Models\Client;
use App\PropertyManagement\Support\ClaimMailSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BulkEmailController extends Controller
{
    public function index()
    {
        if (auth()->user()->role === 'viewer') {
            abort(403, 'ليس لديك صلاحية لإرسال البريد');
        }

        $clients = Client::query()
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->orderBy('name')
            ->get();

        $companyInfo = $this->companyInfo();

        return view('property_management.bulk_email.index', compact('clients', 'companyInfo'));
    }

    public function send(Request $request)
    {
        if (auth()->user()->role === 'viewer') {
            return redirect()->route('property-management.bulk-email.index')
                ->with('error', 'ليس لديك صلاحية لإرسال البريد');
        }

        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string|max:10000',
            'client_ids' => 'required|array|min:1',
            'client_ids.*' => 'integer|exists:clients,id',
        ], [
            'client_ids.required' => 'يجب اختيار شركة واحدة على الأقل',
            'client_ids.min' => 'يجب اختيار شركة واحدة على الأقل',
        ]);

        $clients = Client::query()
            ->whereIn('id', $validated['client_ids'])
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->get();

        if ($clients->isEmpty()) {
            return redirect()->route('property-management.bulk-email.index')
                ->with('error', 'لا يوجد بريد إلكتروني صالح للشركات المحددة')
                ->withInput();
        }

        $apiKey = config('services.resend.api_key');
        if (!$apiKey) {
            return redirect()->route('property-management.bulk-email.index')
                ->with('error', 'خدمة البريد غير مفعّلة. أضف RESEND_API_KEY في Environment ثم نفّذ: php artisan config:clear')
                ->withInput();
        }

        $companyInfo = $this->companyInfo();
        $fromEmail = config('services.resend.from_email');
        $fromName = config('services.resend.from_name');
        $ccEmails = ClaimMailSettings::ccEmails();
        $bccEmails = config('mail.customer_bcc', []);

        $client = new \GuzzleHttp\Client([
            'timeout' => 30,
            'connect_timeout' => 10,
            'verify' => config('services.resend.verify_ssl', true),
            'allow_redirects' => true,
            'http_errors' => true,
        ]);

        $sent = 0;
        $failed = 0;
        $errors = [];

        foreach ($clients as $recipient) {
            $html = view('emails.bulk_message', [
                'client' => $recipient,
                'body' => $validated['body'],
                'companyInfo' => $companyInfo,
            ])->render();

            $emailPayload = [
                'from' => $fromName . ' <' . $fromEmail . '>',
                'to' => [$recipient->email],
                'subject' => $validated['subject'],
                'html' => $html,
            ];

            if (!empty($ccEmails)) {
                $emailPayload['cc'] = $ccEmails;
            }
            if (!empty($bccEmails)) {
                $emailPayload['bcc'] = $bccEmails;
            }

            try {
                $response = $client->post('https://api.resend.com/emails', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $apiKey,
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                    ],
                    'json' => $emailPayload,
                ]);

                $result = json_decode($response->getBody()->getContents(), true);

                EmailLog::create([
                    'rent_payment_id' => null,
                    'contract_id' => null,
                    'client_id' => $recipient->id,
                    'to_email' => $recipient->email,
                    'from_email' => $fromEmail,
                    'subject' => $validated['subject'],
                    'status' => 'sent',
                    'resend_email_id' => $result['id'] ?? null,
                    'sent_by' => auth()->id(),
                    'sent_at' => now(),
                ]);

                $sent++;
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                $response = $e->getResponse();
                $errorBody = $response ? json_decode($response->getBody()->getContents(), true) : null;
                $errorMessage = $errorBody['message'] ?? $e->getMessage();

                EmailLog::create([
                    'rent_payment_id' => null,
                    'contract_id' => null,
                    'client_id' => $recipient->id,
                    'to_email' => $recipient->email,
                    'from_email' => $fromEmail,
                    'subject' => $validated['subject'],
                    'status' => 'failed',
                    'error_message' => $errorMessage,
                    'sent_by' => auth()->id(),
                ]);

                $failed++;
                $errors[] = $recipient->name . ': ' . $errorMessage;
            } catch (\Throwable $e) {
                EmailLog::create([
                    'rent_payment_id' => null,
                    'contract_id' => null,
                    'client_id' => $recipient->id,
                    'to_email' => $recipient->email,
                    'from_email' => $fromEmail,
                    'subject' => $validated['subject'],
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                    'sent_by' => auth()->id(),
                ]);

                $failed++;
                $errors[] = $recipient->name . ': ' . $e->getMessage();
            }
        }

        try {
            activity()
                ->causedBy(auth()->user())
                ->withProperties([
                    'ip_address' => $request->ip(),
                    'action' => 'bulk_email_send',
                    'subject' => $validated['subject'],
                    'sent' => $sent,
                    'failed' => $failed,
                    'client_ids' => $clients->pluck('id')->all(),
                ])
                ->log("إرسال بريد جماعي: {$sent} ناجح، {$failed} فشل");
        } catch (\Exception $e) {
        }

        $message = "تم الإرسال إلى {$sent} شركة/عميل";
        if ($failed > 0) {
            $message .= " — فشل {$failed}";
        }

        return redirect()->route('property-management.bulk-email.index')
            ->with($failed > 0 && $sent === 0 ? 'error' : 'success', $message)
            ->with('send_errors', $errors);
    }

    private function companyInfo(): array
    {
        return [
            'name' => Cache::get('settings.company_name', 'Alzeer Holding'),
            'email' => Cache::get('settings.company_email', 'info@alzeer-holding.com'),
            'phone' => Cache::get('settings.company_phone', ''),
            'address' => Cache::get('settings.company_address', ''),
        ];
    }
}
