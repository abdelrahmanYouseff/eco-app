<?php

namespace App\PropertyManagement\Repositories\Tenants\Interfaces;

use App\PropertyManagement\Models\Client;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface TenantRepositoryInterface
{
    public function find(int $id): ?Client;
    public function findOrFail(int $id): Client;
    public function all(): Collection;
    public function paginate(int $perPage = 15): LengthAwarePaginator;
    public function create(array $data): Client;
    public function update(Client $client, array $data): Client;
    public function delete(Client $client): bool;
    public function findByMobile(string $mobile): ?Client;
    public function findByEmail(string $email): ?Client;
    public function findByNationalId(string $nationalId): ?Client;
    public function search(string $query): Collection;
}


