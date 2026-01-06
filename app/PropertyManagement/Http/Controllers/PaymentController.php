<?php

namespace App\PropertyManagement\Http\Controllers;

use App\PropertyManagement\Models\Contract;
use App\PropertyManagement\Models\RentPayment;
use App\PropertyManagement\Services\Payments\PaymentService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $paymentService
    ) {}

    /**
     * Record a payment
     */
    public function recordPayment(Request $request, int $rentPaymentId): JsonResponse
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        try {
            $rentPayment = RentPayment::findOrFail($rentPaymentId);
            $amount = $request->input('amount');
            $paymentDate = $request->has('payment_date') 
                ? Carbon::parse($request->input('payment_date')) 
                : null;
            $description = $request->input('description');

            $transaction = $this->paymentService->recordPayment(
                $rentPayment,
                $amount,
                $paymentDate,
                $description
            );

            return response()->json([
                'success' => true,
                'message' => 'Payment recorded successfully',
                'data' => $transaction,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get contract payments
     */
    public function contractPayments(int $contractId): JsonResponse
    {
        try {
            $payments = $this->paymentService->getContractPayments($contractId);

            return response()->json([
                'success' => true,
                'data' => $payments,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get pending payments
     */
    public function pending(): JsonResponse
    {
        $payments = $this->paymentService->getPendingPayments();

        return response()->json([
            'success' => true,
            'data' => $payments,
        ]);
    }

    /**
     * Get overdue payments
     */
    public function overdue(): JsonResponse
    {
        $payments = $this->paymentService->getOverduePayments();

        return response()->json([
            'success' => true,
            'data' => $payments,
        ]);
    }

    /**
     * Create adjustment
     */
    public function createAdjustment(Request $request, int $contractId): JsonResponse
    {
        $request->validate([
            'amount' => 'required|numeric',
            'description' => 'required|string',
        ]);

        try {
            $contract = Contract::findOrFail($contractId);
            $transaction = $this->paymentService->createAdjustment(
                $contract,
                $request->input('amount'),
                $request->input('description')
            );

            return response()->json([
                'success' => true,
                'message' => 'Adjustment created successfully',
                'data' => $transaction,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get client balance
     */
    public function clientBalance(int $clientId): JsonResponse
    {
        $balance = $this->paymentService->getClientBalance($clientId);

        return response()->json([
            'success' => true,
            'data' => [
                'client_id' => $clientId,
                'balance' => $balance,
            ],
        ]);
    }
}


