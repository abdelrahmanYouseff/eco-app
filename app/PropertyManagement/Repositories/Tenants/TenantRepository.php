<?php

namespace App\PropertyManagement\Repositories\Tenants;

use App\PropertyManagement\Models\Client;
use App\PropertyManagement\Repositories\Tenants\Interfaces\TenantRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class TenantRepository implements TenantRepositoryInterface
{
    public function find(int $id): ?Client
    {
        return Client::find($id);
    }

    public function findOrFail(int $id): Client
    {
        return Client::findOrFail($id);
    }

    public function all(): Collection
    {
        return Client::with('contracts')->get();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Client::with('contracts')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function create(array $data): Client
    {
        return DB::transaction(function () use ($data) {
            return Client::create($data);
        });
    }

    public function update(Client $client, array $data): Client
    {
        return DB::transaction(function () use ($client, $data) {
            $client->update($data);
            return $client->fresh();
        });
    }

    public function delete(Client $client): bool
    {
        return DB::transaction(function () use ($client) {
            return $client->delete();
        });
    }

    public function findByMobile(string $mobile): ?Client
    {
        return Client::where('mobile', $mobile)->first();
    }

    public function findByEmail(string $email): ?Client
    {
        return Client::where('email', $email)->first();
    }

    public function findByNationalId(string $nationalId): ?Client
    {
        return Client::where('id_number_or_cr', $nationalId)->first();
    }

    public function search(string $query): Collection
    {
        return Client::where('name', 'like', "%{$query}%")
            ->orWhere('mobile', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orWhere('id_number_or_cr', 'like', "%{$query}%")
            ->with('contracts')
            ->get();
    }
}


