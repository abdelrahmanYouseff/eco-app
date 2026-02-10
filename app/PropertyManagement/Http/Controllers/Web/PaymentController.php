<?php

namespace App\PropertyManagement\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\PropertyManagement\Models\Contract;
use App\PropertyManagement\Models\RentPayment;
use App\PropertyManagement\Services\Payments\PaymentService;
use Illuminate\Http\Request;

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
            'contract.broker'
        ])->findOrFail($paymentId);

        return view('property_management.payments.request_payment', compact('payment'));
    }

    public function updatePayment(Request $request, $paymentId)
    {
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
}


