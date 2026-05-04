<?php

namespace App\PropertyManagement\Services\Tenants;

use App\PropertyManagement\Models\Client;
use App\PropertyManagement\Repositories\Tenants\Interfaces\TenantRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class TenantService
{
    public function __construct(
        private TenantRepositoryInterface $tenantRepository
    ) {}

    /**
     * Create a new tenant/client
     */
    public function createTenant(array $data): Client
    {
        // Validate uniqueness
        if (isset($data['mobile']) && $this->tenantRepository->findByMobile($data['mobile'])) {
            throw new \Exception('Mobile number already exists');
        }

        if (isset($data['email']) && $data['email'] && $this->tenantRepository->findByEmail($data['email'])) {
            throw new \Exception('Email already exists');
        }

        if (isset($data['id_number_or_cr']) && $this->tenantRepository->findByNationalId($data['id_number_or_cr'])) {
            throw new \Exception('ID/CR number already exists');
        }

        return $this->tenantRepository->create($data);
    }

    /**
     * Update tenant
     */
    public function updateTenant(Client $client, array $data): Client
    {
        // Validate uniqueness if mobile is being changed
        if (isset($data['mobile']) && $data['mobile'] !== $client->mobile) {
            if ($this->tenantRepository->findByMobile($data['mobile'])) {
                throw new \Exception('Mobile number already exists');
            }
        }

        // Validate uniqueness if email is being changed
        if (isset($data['email']) && $data['email'] !== $client->email) {
            if ($this->tenantRepository->findByEmail($data['email'])) {
                throw new \Exception('Email already exists');
            }
        }

        return $this->tenantRepository->update($client, $data);
    }

    /**
     * Delete tenant
     */
    public function deleteTenant(Client $client): bool
    {
        // Check if tenant has active contracts
        $activeContracts = $client->contracts()
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->count();

        if ($activeContracts > 0) {
            throw new \Exception('Cannot delete tenant with active contracts');
        }

        return $this->tenantRepository->delete($client);
    }

    /**
     * Get tenant account statement
     */
    public function getAccountStatement(Client $client, ?Carbon $fromDate = null, ?Carbon $toDate = null): array
    {
        $fromDate = $fromDate ?? $client->created_at;
        $toDate = $toDate ?? now();

        $contracts = $client->contracts()
            ->whereBetween('start_date', [$fromDate, $toDate])
            ->with(['unit', 'building'])
            ->get();

        $transactions = $client->transactions()
            ->whereBetween('date', [$fromDate, $toDate])
            ->orderBy('date', 'desc')
            ->get();

        $totalDebit = $transactions->where('type', 'payment')->sum('amount');
        $totalCredit = $transactions->whereIn('type', ['adjustment', 'refund'])->sum('amount');
        $balance = $totalDebit - $totalCredit;

        return [
            'client' => $client,
            'contracts' => $contracts,
            'transactions' => $transactions,
            'summary' => [
                'total_debit' => $totalDebit,
                'total_credit' => $totalCredit,
                'balance' => $balance,
            ],
        ];
    }

    /**
     * Search tenants
     */
    public function searchTenants(string $query): Collection
    {
        return $this->tenantRepository->search($query);
    }
}

