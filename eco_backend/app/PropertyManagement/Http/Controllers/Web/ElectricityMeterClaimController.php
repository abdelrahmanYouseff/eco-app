<?php

namespace App\PropertyManagement\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\EmailLog;
use App\PropertyManagement\Models\Client;
use App\PropertyManagement\Models\Contract;
use App\PropertyManagement\Support\ClaimMailSettings;
use Illuminate\Http\Request;

class ElectricityMeterClaimController extends Controller
{
    public function index(Request $request)
    {
        $query = Contract::with(['client', 'unit', 'building'])
            ->orderByDesc('start_date')
            ->orderByDesc('id');

        $claimStatus = $request->input('claim_status', 'all');
        if ($claimStatus === 'sent') {
            $query->whereNotNull('electricity_meter_claim_sent_at');
        } elseif ($claimStatus === 'not_sent') {
            $query->whereNull('electricity_meter_claim_sent_at');
        }

        if ($request->filled('date_from')) {
            $query->whereDate('start_date', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('start_date', '<=', $request->input('date_to'));
        }

        if ($request->filled('company_id')) {
            $query->where('client_id', $request->input('company_id'));
        }

        $contracts = $query->get();

        $companies = Client::where('client_type', 'شركة')
            ->orderBy('name')
            ->get();

        return view('property_management.electricity_meter_claims.index', compact('contracts', 'companies', 'claimStatus'));
    }

    public function preview(Contract $contract)
    {
        $contract->load(['client', 'unit', 'building']);

        return view('property_management.electricity_meter_claims.preview', compact('contract'));
    }

    public function sendEmail(Request $request, Contract $contract)
    {
        if (auth()->user()->role === 'viewer') {
            return response()->json([
                'success' => false,
                'message' => 'ليس لديك صلاحية لإرسال البريد',
            ], 403);
        }

        $contract->load(['client', 'unit', 'building']);

        if (!$contract->client || !$contract->client->email) {
            return response()->json([
                'success' => false,
                'message' => 'لا يوجد بريد إلكتروني مسجل للمستأجر',
            ], 400);
        }

        $apiKey = config('services.resend.api_key');
        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'خدمة البريد غير مفعّلة. أضف RESEND_API_KEY في Environment على السيرفر (Forge) ثم نفّذ: php artisan config:clear',
            ], 500);
        }

        $html = view('emails.electricity_meter_claim', [
            'contract' => $contract,
        ])->render();

        $clientConfig = [
            'timeout' => 30,
            'connect_timeout' => 10,
            'verify' => config('services.resend.verify_ssl', true),
            'allow_redirects' => true,
            'http_errors' => true,
        ];

        $client = new \GuzzleHttp\Client($clientConfig);
        $fromEmail = config('services.resend.from_email');
        $fromName = config('services.resend.from_name');
        $toEmail = $contract->client->email;
        $ccEmails = ClaimMailSettings::ccEmails();
        $bccEmails = config('mail.customer_bcc', []);
        $subject = 'إشعار للمستأجر بنقل عداد الكهرباء - عقد رقم ' . $contract->contract_number;

        $emailPayload = [
            'from' => $fromName . ' <' . $fromEmail . '>',
            'to' => [$toEmail],
            'subject' => $subject,
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

            $contract->electricity_meter_claim_sent_at = now();
            $contract->save();

            try {
                EmailLog::create([
                    'rent_payment_id' => null,
                    'contract_id' => $contract->id,
                    'client_id' => $contract->client->id,
                    'to_email' => $toEmail,
                    'from_email' => $fromEmail,
                    'subject' => $subject,
                    'status' => 'sent',
                    'resend_email_id' => $result['id'] ?? null,
                    'sent_by' => auth()->id(),
                    'sent_at' => now(),
                ]);
            } catch (\Exception $e) {
                // ignore log failures
            }

            try {
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($contract)
                    ->withProperties([
                        'ip_address' => $request->ip(),
                        'action' => 'send_electricity_meter_claim_email',
                        'email' => $toEmail,
                    ])
                    ->log("تم إرسال مطالبة عداد الكهرباء بالبريد - العقد: {$contract->contract_number}");
            } catch (\Exception $e) {
            }

            return response()->json([
                'success' => true,
                'message' => 'تم إرسال المطالبة بنجاح إلى ' . $toEmail,
                'email_id' => $result['id'] ?? null,
            ]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $errorBody = $response ? json_decode($response->getBody()->getContents(), true) : null;
            $errorMessage = $errorBody['message'] ?? $e->getMessage();

            try {
                EmailLog::create([
                    'rent_payment_id' => null,
                    'contract_id' => $contract->id,
                    'client_id' => $contract->client->id,
                    'to_email' => $toEmail,
                    'from_email' => $fromEmail,
                    'subject' => $subject,
                    'status' => 'failed',
                    'error_message' => $errorMessage,
                    'sent_by' => auth()->id(),
                ]);
            } catch (\Exception $logError) {
            }

            return response()->json([
                'success' => false,
                'message' => 'فشل إرسال البريد: ' . $errorMessage,
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'فشل إرسال البريد: ' . $e->getMessage(),
            ], 500);
        }
    }
}
