<?php

use App\Http\Controllers\owner\Auth\LoginController;
use App\Http\Controllers\owner\CompanyController;
use App\Http\Controllers\owner\UserController;
use App\Http\Controllers\owner\ServiceController;
use App\Http\Controllers\owner\EventController;
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
    Route::get('/add/new-service', [ServiceController::class, 'addNewServiceView'])->name('service.view');
    Route::post('/services/store', [ServiceController::class, 'store'])->name('services.store');
    Route::delete('/services/{id}', [ServiceController::class, 'destroy'])->name('services.destroy');
    Route::get('/services/request', [ServiceController::class, 'requestView'])->name('request.view');
    Route::get('/event/view', [EventController::class, 'eventView'])->name('event.view');
    Route::post('/announcements/store', [EventController::class, 'store'])->name('event.store');

});


Route::get('/building/admin/dashboard', [LoginController::class, 'adminDashboardView'])
->name('building.admin.dashboard');
