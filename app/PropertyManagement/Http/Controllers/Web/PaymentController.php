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
}


