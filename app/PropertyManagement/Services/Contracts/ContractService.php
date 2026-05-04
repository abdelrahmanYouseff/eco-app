<?php

namespace App\PropertyManagement\Services\Contracts;

use App\PropertyManagement\Models\Contract;
use App\PropertyManagement\Models\ContractRepresentative;
use App\PropertyManagement\Repositories\Contracts\Interfaces\ContractRepositoryInterface;
use App\PropertyManagement\Services\Payments\PaymentService;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ContractService
{
    public function __construct(
        private ContractRepositoryInterface $contractRepository,
        private PaymentService $paymentService
    ) {}

    /**
     * Create a new contract with representatives
     */
    public function createContract(array $contractData, array $representatives = []): Contract
    {
        // Generate contract number if not provided
        if (!isset($contractData['contract_number'])) {
            $contractData['contract_number'] = $this->generateContractNumber();
        }

        // Validate contract number uniqueness
        if ($this->contractRepository->findByContractNumber($contractData['contract_number'])) {
            throw new \Exception('Contract number already exists');
        }

        // Create contract
        $contract = $this->contractRepository->create($contractData);

        // Create representatives
        foreach ($representatives as $representativeData) {
            $representativeData['contract_id'] = $contract->id;
            ContractRepresentative::create($representativeData);
        }

        // Generate payment schedule
        $this->paymentService->generatePaymentSchedule($contract);

        return $contract->load(['building', 'unit', 'client', 'broker', 'representatives']);
    }

    /**
     * Update contract
     */
    public function updateContract(Contract $contract, array $data): Contract
    {
        // Check if contract number is being changed and validate uniqueness
        if (isset($data['contract_number']) && $data['contract_number'] !== $contract->contract_number) {
            if ($this->contractRepository->findByContractNumber($data['contract_number'])) {
                throw new \Exception('Contract number already exists');
            }
        }

        return $this->contractRepository->update($contract, $data);
    }

    /**
     * Terminate contract
     */
    public function terminateContract(Contract $contract, Carbon $terminationDate, string $reason = null): Contract
    {
        if ($terminationDate->isBefore($contract->start_date)) {
            throw new \Exception('Termination date cannot be before contract start date');
        }

        $contract->update([
            'end_date' => $terminationDate,
        ]);

        // Cancel future payments
        $this->paymentService->cancelFuturePayments($contract, $terminationDate);

        return $contract->fresh();
    }

    /**
     * Renew contract
     */
    public function renewContract(Contract $contract, array $newContractData): Contract
    {
        // Terminate old contract
        $this->terminateContract($contract, now());

        // Create new contract with renewal type
        $newContractData['contract_type'] = 'مجدد';
        $newContractData['building_id'] = $contract->building_id;
        $newContractData['unit_id'] = $contract->unit_id;
        $newContractData['client_id'] = $contract->client_id;

        return $this->createContract($newContractData);
    }

    /**
     * Calculate contract due amounts
     */
    public function calculateDueAmounts(Contract $contract): array
    {
        $payments = $this->paymentService->getContractPayments($contract->id);

        $totalDue = $payments->whereIn('status', ['unpaid', 'partially_paid'])->sum('total_value');
        $totalPaid = $payments->where('status', 'paid')->sum('total_value');
        $overdue = $payments->where('status', '!=', 'paid')
            ->where('due_date', '<', now())
            ->sum('total_value');

        return [
            'total_due' => $totalDue,
            'total_paid' => $totalPaid,
            'overdue' => $overdue,
            'remaining' => $totalDue - $overdue,
        ];
    }

    /**
     * Get active contracts
     */
    public function getActiveContracts(): Collection
    {
        return $this->contractRepository->getActiveContracts();
    }

    /**
     * Get expired contracts
     */
    public function getExpiredContracts(): Collection
    {
        return $this->contractRepository->getExpiredContracts();
    }

    /**
     * Generate unique contract number
     */
    private function generateContractNumber(): string
    {
        $year = now()->year;
        $lastContract = Contract::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        $number = $lastContract ? (int) substr($lastContract->contract_number, -4) + 1 : 1;

        return "CONTRACT-{$year}-" . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}

