<?php

namespace App\PropertyManagement\Repositories\Contracts\Interfaces;

use App\PropertyManagement\Models\Contract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ContractRepositoryInterface
{
    public function find(int $id): ?Contract;
    public function findOrFail(int $id): Contract;
    public function all(): Collection;
    public function paginate(int $perPage = 15): LengthAwarePaginator;
    public function create(array $data): Contract;
    public function update(Contract $contract, array $data): Contract;
    public function delete(Contract $contract): bool;
    public function findByContractNumber(string $contractNumber): ?Contract;
    public function getByClientId(int $clientId): Collection;
    public function getByUnitId(int $unitId): Collection;
    public function getActiveContracts(): Collection;
    public function getExpiredContracts(): Collection;
}


