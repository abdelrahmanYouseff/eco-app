<?php

namespace App\PropertyManagement\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Mail\PaymentRequestEmail;
use App\Models\EmailLog;
use App\PropertyManagement\Models\Contract;
use App\PropertyManagement\Models\RentPayment;
use App\PropertyManagement\Services\Payments\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $paymentService
    ) {}

    public function index(Request $request)
    {
        $type = $request->input('type', 'pending');

        if ($type === 'overdue') {
            $payments = $this->paymentService->getOverduePayments();
        } else {
            $payments = $this->paymentService->getPendingPayments();
        }

        return view('property_management.payments.index', compact('payments', 'type'));
    }

    public function contractPayments($contractId)
    {
        $contract = Contract::findOrFail($contractId);
        $payments = $this->paymentService->getContractPayments($contractId);

        return view('property_management.payments.contract', compact('contract', 'payments'));
    }

    public function requestPayment($paymentId)
    {
        $payment = RentPayment::with([
            'contract.client',
            'contract.unit',
            'contract.building',
            'contract.broker',
            'contract.rentPayments'
        ])->findOrFail($paymentId);

        return view('property_management.payments.request_payment', compact('payment'));
    }

    public function updatePayment(Request $request, $paymentId)
    {
        // Prevent viewer role from updating payments
        if (auth()->user()->role === 'viewer') {
            return response()->json([
                'success' => false,
                'message' => 'ليس لديك صلاحية لتعديل الدفعات',
            ], 403);
        }

        $payment = RentPayment::findOrFail($paymentId);

        $validated = $request->validate([
            'rent_value' => 'nullable|numeric|min:0',
            'fixed_amounts' => 'nullable|numeric|min:0',
            'total_value' => 'nullable|numeric|min:0',
        ]);

        // تحديث القيم المقدمة فقط
        if (isset($validated['rent_value'])) {
            $payment->rent_value = $validated['rent_value'];
        }
        if (isset($validated['fixed_amounts'])) {
            $payment->fixed_amounts = $validated['fixed_amounts'];
        }
        if (isset($validated['total_value'])) {
            $payment->total_value = $validated['total_value'];
        } else {
            // إذا لم يتم تحديث الإجمالي مباشرة، إعادة حسابه
            $payment->total_value = $payment->rent_value + $payment->services_value + $payment->vat_value + ($payment->fixed_amounts ?? 0);
        }

        $payment->save();

        // Log custom activity for payment update
        try {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($payment->contract)
                ->withProperties([
                    'ip_address' => request()->ip(),
                    'payment_id' => $payment->id,
                    'action' => 'update_payment',
                ])
                ->log("تم تحديث دفعة الإيجار - العقد: " . ($payment->contract->contract_number ?? 'N/A'));
        } catch (\Exception $e) {
            // Silently fail if activity_log table doesn't exist
        }

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الدفعة بنجاح',
            'payment' => [
                'rent_value' => $payment->rent_value,
                'services_value' => $payment->services_value,
                'vat_value' => $payment->vat_value,
                'fixed_amounts' => $payment->fixed_amounts,
                'total_value' => $payment->total_value,
            ]
        ]);
    }

    public function sendPaymentRequestEmail($paymentId)
    {
        try {
            $payment = RentPayment::with([
                'contract.client',
                'contract.unit',
                'contract.building',
                'contract.broker',
                'contract.rentPayments'
            ])->findOrFail($paymentId);

            // Check if client has email
            if (!$payment->contract->client->email) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا يوجد بريد إلكتروني مسجل للعميل'
                ], 400);
            }

            // Send email using Resend API directly
            $apiKey = env('RESEND_API_KEY');
            
            if (!$apiKey) {
                return response()->json([
                    'success' => false,
                    'message' => 'RESEND_API_KEY is not set in .env file'
                ], 500);
            }

            // Render the email view
            $html = view('emails.payment_request', compact('payment'))->render();

            // Send email using Resend API directly via HTTP
            $clientConfig = [
                'timeout' => 30,
                'connect_timeout' => 10,
                'verify' => env('RESEND_VERIFY_SSL', true),
                'allow_redirects' => true,
                'http_errors' => true,
            ];

            $client = new \GuzzleHttp\Client($clientConfig);

            $fromEmail = env('RESEND_FROM_EMAIL', 'info@alzeer-holding.com');
            $fromName = 'Fahad Nawaf Alzeer Holding';
            $toEmail = $payment->contract->client->email;
            $subject = "مطالبة مالية - عقد رقم {$payment->contract->contract_number}";

            $response = $client->post('https://api.resend.com/emails', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'json' => [
                    'from' => $fromName . ' <' . $fromEmail . '>',
                    'to' => [$toEmail],
                    'subject' => $subject,
                    'html' => $html,
                ],
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            // Save email log
            try {
                EmailLog::create([
                    'rent_payment_id' => $payment->id,
                    'contract_id' => $payment->contract->id,
                    'client_id' => $payment->contract->client->id,
                    'to_email' => $toEmail,
                    'from_email' => $fromEmail,
                    'subject' => $subject,
                    'status' => 'sent',
                    'resend_email_id' => $result['id'] ?? null,
                    'sent_by' => auth()->id(),
                    'sent_at' => now(),
                ]);
            } catch (\Exception $e) {
                // Silently fail if email_logs table doesn't exist
            }

            // Log activity
            try {
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($payment->contract)
                    ->withProperties([
                        'ip_address' => request()->ip(),
                        'payment_id' => $payment->id,
                        'action' => 'send_payment_request_email',
                        'email' => $toEmail,
                    ])
                    ->log("تم إرسال مطالبة مالية بالبريد الإلكتروني - العقد: {$payment->contract->contract_number}");
            } catch (\Exception $e) {
                // Silently fail if activity_log table doesn't exist
            }

            return response()->json([
                'success' => true,
                'message' => 'تم إرسال المطالبة المالية بنجاح إلى ' . $toEmail,
                'email_id' => $result['id'] ?? null
            ]);

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $errorBody = $response ? json_decode($response->getBody()->getContents(), true) : null;
            $errorMessage = $errorBody['message'] ?? $e->getMessage();

            // Save failed email log
            try {
                $contractNumber = $payment && $payment->contract ? $payment->contract->contract_number : 'N/A';
                EmailLog::create([
                    'rent_payment_id' => $payment ? $payment->id : null,
                    'contract_id' => $payment && $payment->contract ? $payment->contract->id : null,
                    'client_id' => $payment && $payment->contract && $payment->contract->client ? $payment->contract->client->id : null,
                    'to_email' => $payment && $payment->contract && $payment->contract->client ? ($payment->contract->client->email ?? 'unknown') : 'unknown',
                    'from_email' => env('RESEND_FROM_EMAIL', 'info@alzeer-holding.com'),
                    'subject' => "مطالبة مالية - عقد رقم {$contractNumber}",
                    'status' => 'failed',
                    'error_message' => $errorMessage,
                    'sent_by' => auth()->id(),
                ]);
            } catch (\Exception $logError) {
                // Silently fail
            }

            return response()->json([
                'success' => false,
                'message' => 'فشل إرسال البريد: ' . $errorMessage,
                'error_details' => $errorBody ?? null
            ], 500);
        } catch (\Exception $e) {
            // Save failed email log
            try {
                if (isset($payment) && $payment) {
                    $contractNumber = $payment->contract ? $payment->contract->contract_number : 'N/A';
                    EmailLog::create([
                        'rent_payment_id' => $payment->id,
                        'contract_id' => $payment->contract ? $payment->contract->id : null,
                        'client_id' => $payment->contract && $payment->contract->client ? $payment->contract->client->id : null,
                        'to_email' => $payment->contract && $payment->contract->client ? ($payment->contract->client->email ?? 'unknown') : 'unknown',
                        'from_email' => env('RESEND_FROM_EMAIL', 'info@alzeer-holding.com'),
                        'subject' => "مطالبة مالية - عقد رقم {$contractNumber}",
                        'status' => 'failed',
                        'error_message' => $e->getMessage(),
                        'sent_by' => auth()->id(),
                    ]);
                }
            } catch (\Exception $logError) {
                // Silently fail
            }

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage()
            ], 500);
        }
    }
}


