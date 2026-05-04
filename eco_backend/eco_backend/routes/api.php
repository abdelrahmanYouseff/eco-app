<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\GateController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\AnnouncementController;
use App\Http\Controllers\Api\VisitorController;
use App\Http\Controllers\Api\MaintenanceRequestController;
<<<<<<< HEAD:eco_backend/eco_backend/routes/api.php
=======
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\WebhookController;
>>>>>>> 214f60c165b7db18fb2afc2c6aa07b4401e9122d:routes/api.php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/companies/{id}', [CompanyController::class, 'show']);
    Route::post('/register', [UserController::class, 'register']);
    Route::get('/companies/{companyId}/employees', [UserController::class, 'employeesByCompany']);

    // Live location updates (mobile app)
    Route::post('/locations', [LocationController::class, 'store']);
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/open-gate', [GateController::class, 'open']);

Route::get('/announcements', [AnnouncementController::class, 'index']);
Route::post('/visitors', [VisitorController::class, 'store']);
Route::get('/visitors/by-user-company/{userId}', [VisitorController::class, 'getVisitorsByUserCompany']);
Route::post('/maintenance-requests', [MaintenanceRequestController::class, 'store']);
