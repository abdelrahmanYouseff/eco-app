<?php

namespace App\PropertyManagement\Repositories\Contracts;

use App\PropertyManagement\Models\Contract;
use App\PropertyManagement\Repositories\Contracts\Interfaces\ContractRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ContractRepository implements ContractRepositoryInterface
{
    public function find(int $id): ?Contract
    {
        return Contract::find($id);
    }

    public function findOrFail(int $id): Contract
    {
        return Contract::findOrFail($id);
    }

    public function all(): Collection
    {
        return Contract::with(['building', 'unit', 'client', 'broker'])->get();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Contract::with(['building', 'unit', 'client', 'broker'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function create(array $data): Contract
    {
        return DB::transaction(function () use ($data) {
            return Contract::create($data);
        });
    }

    public function update(Contract $contract, array $data): Contract
    {
        return DB::transaction(function () use ($contract, $data) {
            $contract->update($data);
            return $contract->fresh();
        });
    }

    public function delete(Contract $contract): bool
    {
        return DB::transaction(function () use ($contract) {
            return $contract->delete();
        });
    }

    public function findByContractNumber(string $contractNumber): ?Contract
    {
        return Contract::where('contract_number', $contractNumber)->first();
    }

    public function getByClientId(int $clientId): Collection
    {
        return Contract::where('client_id', $clientId)
            ->with(['building', 'unit', 'broker'])
            ->get();
    }

    public function getByUnitId(int $unitId): Collection
    {
        return Contract::where('unit_id', $unitId)
            ->with(['client', 'broker'])
            ->get();
    }

    public function getActiveContracts(): Collection
    {
        $today = now()->toDateString();
        return Contract::where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->with(['building', 'unit', 'client'])
            ->get();
    }

    public function getExpiredContracts(): Collection
    {
        $today = now()->toDateString();
        return Contract::where('end_date', '<', $today)
            ->with(['building', 'unit', 'client'])
            ->get();
    }
}

