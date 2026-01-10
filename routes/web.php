<?php

use App\Http\Controllers\owner\Auth\LoginController;
use App\Http\Controllers\owner\CompanyController;
use App\Http\Controllers\owner\UserController;
use App\Http\Controllers\owner\ServiceController;
use App\Http\Controllers\owner\EventController;
use App\Http\Controllers\owner\GatesController;
use App\Http\Controllers\Api\WebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});


Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login'); // عرض نموذج تسجيل الدخول
Route::post('/login', [LoginController::class, 'login']); // معالجة تسجيل الدخول


Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout'); // معالجة تسجيل الخروج

    Route::get('/building/owner/dashboard', [LoginController::class, 'ownerDashboardView'])
        ->name('building.owner.dashboard');

    Route::get('/company/add', [CompanyController::class, 'addCompanyView'])->name('company.add');
    Route::get('/company/list', [CompanyController::class, 'CompanyList'])->name('company.list');
    Route::post('/companies/store', [CompanyController::class, 'store'])->name('companies.store');


    Route::get('/user/add', [UserController::class, 'addNewUserView'])->name('user.add');
    Route::get('/user/list', [UserController::class, 'userList'])->name('user.list');
    Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{id}/change-password', [UserController::class, 'changePassword'])->name('users.change-password');

    // Gates Routes
    Route::get('/gates/access-logs', [GatesController::class, 'accessLogs'])->name('gates.access.logs');
    Route::get('/gates/access-logs/api', [GatesController::class, 'getAccessLogs'])->name('gates.access.logs.api');
    Route::get('/gates/user-status/{userId}', [GatesController::class, 'getUserStatus'])->name('gates.user.status');
    Route::get('/gates/qr-code/{badgeId}', [GatesController::class, 'generateQRCode'])->name('gates.qr.code');

    Route::get('/add/new-service', [ServiceController::class, 'addNewServiceView'])->name('service.view');
    Route::post('/services/store', [ServiceController::class, 'store'])->name('services.store');
    Route::delete('/services/{id}', [ServiceController::class, 'destroy'])->name('services.destroy');
    Route::get('/services/request', [ServiceController::class, 'requestView'])->name('request.view');
    Route::get('/event/view', [EventController::class, 'eventView'])->name('event.view');
    Route::post('/announcements/store', [EventController::class, 'store'])->name('event.store');

    // Property Management Routes
    Route::prefix('property-management')->name('property-management.')->group(function () {
        // Buildings
        Route::get('/buildings', [\App\PropertyManagement\Http\Controllers\Web\BuildingController::class, 'index'])->name('buildings.index');
        Route::get('/buildings/create', [\App\PropertyManagement\Http\Controllers\Web\BuildingController::class, 'create'])->name('buildings.create');
        Route::post('/buildings', [\App\PropertyManagement\Http\Controllers\Web\BuildingController::class, 'store'])->name('buildings.store');
        Route::get('/buildings/{id}', [\App\PropertyManagement\Http\Controllers\Web\BuildingController::class, 'show'])->name('buildings.show');
        Route::get('/buildings/{id}/edit', [\App\PropertyManagement\Http\Controllers\Web\BuildingController::class, 'edit'])->name('buildings.edit');
        Route::put('/buildings/{id}', [\App\PropertyManagement\Http\Controllers\Web\BuildingController::class, 'update'])->name('buildings.update');
        Route::delete('/buildings/{id}', [\App\PropertyManagement\Http\Controllers\Web\BuildingController::class, 'destroy'])->name('buildings.destroy');

        // Units
        Route::get('/units', [\App\PropertyManagement\Http\Controllers\Web\UnitController::class, 'index'])->name('units.index');
        Route::get('/units/create', [\App\PropertyManagement\Http\Controllers\Web\UnitController::class, 'create'])->name('units.create');
        Route::post('/units', [\App\PropertyManagement\Http\Controllers\Web\UnitController::class, 'store'])->name('units.store');
        Route::delete('/units/bulk-delete', [\App\PropertyManagement\Http\Controllers\Web\UnitController::class, 'bulkDelete'])->name('units.bulk-delete');
        Route::get('/units/{id}', [\App\PropertyManagement\Http\Controllers\Web\UnitController::class, 'show'])->name('units.show');
        Route::get('/units/{id}/edit', [\App\PropertyManagement\Http\Controllers\Web\UnitController::class, 'edit'])->name('units.edit');
        Route::put('/units/{id}', [\App\PropertyManagement\Http\Controllers\Web\UnitController::class, 'update'])->name('units.update');
        Route::delete('/units/{id}', [\App\PropertyManagement\Http\Controllers\Web\UnitController::class, 'destroy'])->name('units.destroy');

        // Contracts
        Route::get('/contracts', [\App\PropertyManagement\Http\Controllers\Web\ContractController::class, 'index'])->name('contracts.index');
        Route::get('/contracts/create', [\App\PropertyManagement\Http\Controllers\Web\ContractController::class, 'create'])->name('contracts.create');
        Route::post('/contracts', [\App\PropertyManagement\Http\Controllers\Web\ContractController::class, 'store'])->name('contracts.store');
        Route::delete('/contracts/bulk-delete', [\App\PropertyManagement\Http\Controllers\Web\ContractController::class, 'bulkDelete'])->name('contracts.bulk-delete');
        Route::post('/contracts/{contractId}/payments/{paymentId}/mark-as-paid', [\App\PropertyManagement\Http\Controllers\Web\ContractController::class, 'markPaymentAsPaid'])->name('contracts.payments.mark-as-paid');
        Route::get('/contracts/{id}', [\App\PropertyManagement\Http\Controllers\Web\ContractController::class, 'show'])->name('contracts.show');

        // Tenants
        Route::get('/tenants', [\App\PropertyManagement\Http\Controllers\Web\TenantController::class, 'index'])->name('tenants.index');
        Route::get('/tenants/create', [\App\PropertyManagement\Http\Controllers\Web\TenantController::class, 'create'])->name('tenants.create');
        Route::post('/tenants', [\App\PropertyManagement\Http\Controllers\Web\TenantController::class, 'store'])->name('tenants.store');
        Route::get('/tenants/account-statements', [\App\PropertyManagement\Http\Controllers\Web\TenantController::class, 'customerAccountStatements'])->name('tenants.account-statements');
        Route::get('/tenants/{id}', [\App\PropertyManagement\Http\Controllers\Web\TenantController::class, 'show'])->name('tenants.show');
        Route::delete('/tenants/{id}', [\App\PropertyManagement\Http\Controllers\Web\TenantController::class, 'destroy'])->name('tenants.destroy');

        // Payments
        Route::get('/payments', [\App\PropertyManagement\Http\Controllers\Web\PaymentController::class, 'index'])->name('payments.index');
        Route::get('/payments/{paymentId}/request-payment', [\App\PropertyManagement\Http\Controllers\Web\PaymentController::class, 'requestPayment'])->name('payments.request-payment');
        Route::get('/payments/contract/{contractId}', [\App\PropertyManagement\Http\Controllers\Web\PaymentController::class, 'contractPayments'])->name('payments.contract');

        // Invoices
        Route::get('/invoices', [\App\PropertyManagement\Http\Controllers\Web\InvoiceController::class, 'index'])->name('invoices.index');
        Route::get('/invoices/{id}', [\App\PropertyManagement\Http\Controllers\Web\InvoiceController::class, 'show'])->name('invoices.show');

        // Receipt Vouchers
        Route::get('/receipt-vouchers', [\App\PropertyManagement\Http\Controllers\Web\ReceiptVoucherController::class, 'index'])->name('receipt-vouchers.index');
        Route::get('/receipt-vouchers/{id}', [\App\PropertyManagement\Http\Controllers\Web\ReceiptVoucherController::class, 'show'])->name('receipt-vouchers.show');

        // Notifications
        Route::get('/notifications', [\App\PropertyManagement\Http\Controllers\Web\NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/{id}/mark-as-read', [\App\PropertyManagement\Http\Controllers\Web\NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
        Route::post('/notifications/mark-all-as-read', [\App\PropertyManagement\Http\Controllers\Web\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');
        Route::delete('/notifications/{id}', [\App\PropertyManagement\Http\Controllers\Web\NotificationController::class, 'destroy'])->name('notifications.destroy');

        // Accounting
        Route::get('/accounting', [\App\PropertyManagement\Http\Controllers\Web\AccountingController::class, 'index'])->name('accounting.index');
        Route::get('/accounting/revenues', [\App\PropertyManagement\Http\Controllers\Web\AccountingController::class, 'revenues'])->name('accounting.revenues');
        Route::get('/accounting/revenues/export', [\App\PropertyManagement\Http\Controllers\Web\AccountingController::class, 'exportRevenues'])->name('accounting.revenues.export');
        Route::get('/accounting/pending', [\App\PropertyManagement\Http\Controllers\Web\AccountingController::class, 'pending'])->name('accounting.pending');
        Route::get('/accounting/pending/export', [\App\PropertyManagement\Http\Controllers\Web\AccountingController::class, 'exportPending'])->name('accounting.pending.export');
        Route::get('/accounting/invoices', [\App\PropertyManagement\Http\Controllers\Web\AccountingController::class, 'invoices'])->name('accounting.invoices');
        Route::get('/accounting/invoices/export', [\App\PropertyManagement\Http\Controllers\Web\AccountingController::class, 'exportInvoices'])->name('accounting.invoices.export');

        // Brokers
        Route::get('/brokers', [\App\PropertyManagement\Http\Controllers\Web\BrokerController::class, 'index'])->name('brokers.index');
        Route::get('/brokers/create', [\App\PropertyManagement\Http\Controllers\Web\BrokerController::class, 'create'])->name('brokers.create');
        Route::post('/brokers', [\App\PropertyManagement\Http\Controllers\Web\BrokerController::class, 'store'])->name('brokers.store');
        Route::get('/brokers/{id}', [\App\PropertyManagement\Http\Controllers\Web\BrokerController::class, 'show'])->name('brokers.show');
        Route::get('/brokers/{id}/edit', [\App\PropertyManagement\Http\Controllers\Web\BrokerController::class, 'edit'])->name('brokers.edit');
        Route::put('/brokers/{id}', [\App\PropertyManagement\Http\Controllers\Web\BrokerController::class, 'update'])->name('brokers.update');
        Route::delete('/brokers/{id}', [\App\PropertyManagement\Http\Controllers\Web\BrokerController::class, 'destroy'])->name('brokers.destroy');

        // Settings
        Route::get('/settings', [\App\PropertyManagement\Http\Controllers\Web\SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings', [\App\PropertyManagement\Http\Controllers\Web\SettingsController::class, 'update'])->name('settings.update');
    });

});


Route::get('/building/admin/dashboard', [LoginController::class, 'adminDashboardView'])
->name('building.admin.dashboard');

// Privacy Policy Route
Route::get('/privacy', function () {
    return view('privacy');
})->name('privacy');

// Webhook Routes
Route::get('/webhook/requests', [WebhookController::class, 'show'])->name('webhook.requests');

// Maintenance Request Route
Route::get('/services/request', function () {
    return view('maintenance.request');
})->name('maintenance.request');
