<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\ComplaintPhotoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HrRequestController;
use App\Http\Controllers\PushSubscriptionController;
use App\Http\Controllers\ReporterController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('form'))->name('home');
Route::post('/complaint/submit', [ComplaintController::class, 'store'])->name('complaint.store');
Route::post('/hr-requests/submit', [HrRequestController::class, 'store'])->name('hr-requests.store');

Route::get('/tiket/{ticket}', [TicketController::class, 'show'])->name('ticket.show');
Route::get('/api/cek-tiket', [TicketController::class, 'check'])->name('api.cek-tiket');
Route::get('/complaint/success', [TicketController::class, 'success'])->name('ticket.success');
Route::get('/complaint-photos/{filename}', [ComplaintPhotoController::class, 'show'])->name('complaint.photos.show');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::get('/api/push/public-key', [PushSubscriptionController::class, 'publicKey'])->name('api.push.public-key');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/complaints', [ComplaintController::class, 'index'])->name('complaints.index');
    Route::get('/complaints/{complaint}', [ComplaintController::class, 'show'])->name('complaints.show');
    Route::patch('/complaints/{complaint}/status', [ComplaintController::class, 'updateStatus'])->name('complaints.status');

    Route::get('/api/new-complaints', [DashboardController::class, 'newComplaints'])->name('api.new-complaints');
    Route::get('/api/dashboard-stats', [DashboardController::class, 'stats'])->name('api.dashboard-stats');
    Route::post('/api/push/subscribe', [PushSubscriptionController::class, 'store'])->name('api.push.subscribe');
    Route::delete('/api/push/unsubscribe', [PushSubscriptionController::class, 'destroy'])->name('api.push.unsubscribe');

    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/reporters', [ReporterController::class, 'index'])->name('reporters.index');

    Route::middleware('role:superadmin')->group(function () {
        Route::get('/hr-dashboard', [HrRequestController::class, 'dashboard'])->name('hr.dashboard');
        Route::get('/hr-requests', [HrRequestController::class, 'index'])->name('hr.requests.index');
        Route::get('/hr-requests/{hrRequest}', [HrRequestController::class, 'show'])->name('hr.requests.show');
        Route::patch('/hr-requests/{hrRequest}/status', [HrRequestController::class, 'updateStatus'])->name('hr.requests.status');

        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
        Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    });
});

