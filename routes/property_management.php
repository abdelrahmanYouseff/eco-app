<?php

use App\PropertyManagement\Http\Controllers\ContractController;
use App\PropertyManagement\Http\Controllers\PaymentController;
use App\PropertyManagement\Http\Controllers\TenantController;
use App\PropertyManagement\Http\Controllers\UnitController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Property Management Routes
|--------------------------------------------------------------------------
|
| Here are the routes for the Property Management module.
| All routes are prefixed with 'api/property-management'
|
*/

Route::prefix('api/property-management')->middleware(['auth:sanctum'])->group(function () {
    
    // Contracts Routes
    Route::prefix('contracts')->group(function () {
        Route::get('/', [ContractController::class, 'index']);
        Route::post('/', [ContractController::class, 'store']);
        Route::get('/{id}', [ContractController::class, 'show']);
        Route::put('/{id}', [ContractController::class, 'update']);
        Route::post('/{id}/terminate', [ContractController::class, 'terminate']);
        Route::get('/{id}/due-amounts', [ContractController::class, 'dueAmounts']);
    });

    // Tenants/Clients Routes
    Route::prefix('tenants')->group(function () {
        Route::get('/', [TenantController::class, 'index']);
        Route::post('/', [TenantController::class, 'store']);
        Route::get('/search', [TenantController::class, 'search']);
        Route::get('/{id}', [TenantController::class, 'show']);
        Route::put('/{id}', [TenantController::class, 'update']);
        Route::get('/{id}/account-statement', [TenantController::class, 'accountStatement']);
    });

    // Units Routes
    Route::prefix('units')->group(function () {
        Route::get('/', [UnitController::class, 'index']);
        Route::post('/', [UnitController::class, 'store']);
        Route::get('/available', [UnitController::class, 'available']);
        Route::get('/occupied', [UnitController::class, 'occupied']);
        Route::get('/{id}', [UnitController::class, 'show']);
        Route::put('/{id}', [UnitController::class, 'update']);
    });

    // Payments Routes
    Route::prefix('payments')->group(function () {
        Route::post('/record/{rentPaymentId}', [PaymentController::class, 'recordPayment']);
        Route::get('/contract/{contractId}', [PaymentController::class, 'contractPayments']);
        Route::get('/pending', [PaymentController::class, 'pending']);
        Route::get('/overdue', [PaymentController::class, 'overdue']);
        Route::post('/adjustment/{contractId}', [PaymentController::class, 'createAdjustment']);
        Route::get('/client/{clientId}/balance', [PaymentController::class, 'clientBalance']);
    });
});


