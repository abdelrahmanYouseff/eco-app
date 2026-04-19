<?php

namespace App\PropertyManagement\Repositories\Payments\Interfaces;

use App\PropertyManagement\Models\RentPayment;
use App\PropertyManagement\Models\Transaction;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface PaymentRepositoryInterface
{
    // Rent Payments
    public function findRentPayment(int $id): ?RentPayment;
    public function createRentPayment(array $data): RentPayment;
    public function updateRentPayment(RentPayment $payment, array $data): RentPayment;
    public function getRentPaymentsByContract(int $contractId): Collection;
    public function getPendingRentPayments(): Collection;
    public function getOverdueRentPayments(): Collection;
    
    // Transactions
    public function findTransaction(int $id): ?Transaction;
    public function createTransaction(array $data): Transaction;
    public function getTransactionsByContract(int $contractId): Collection;
    public function getTransactionsByClient(int $clientId): Collection;
    public function getClientBalance(int $clientId): float;
}


