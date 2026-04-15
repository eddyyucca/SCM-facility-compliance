<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ReporterController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserManagementController;

// ── Public: Complaint form ──────────────────────────────────
Route::get('/', function () { return view('welcome'); })->name('home');
Route::post('/complaint/submit', [ComplaintController::class, 'store'])->name('complaint.store');

// ── Public: Ticket status ───────────────────────────────────
Route::get('/tiket/{ticket}',    [TicketController::class, 'show'])->name('ticket.show');
Route::get('/api/cek-tiket',     [TicketController::class, 'check'])->name('api.cek-tiket');
Route::get('/complaint/success', [TicketController::class, 'success'])->name('ticket.success');

// ── Auth ────────────────────────────────────────────────────
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ── Protected: Dashboard, Complaints, Analytics ─────────────
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Complaints
    Route::get('/complaints',                            [ComplaintController::class, 'index'])->name('complaints.index');
    Route::get('/complaints/{complaint}',                [ComplaintController::class, 'show'])->name('complaints.show');
    Route::patch('/complaints/{complaint}/status',       [ComplaintController::class, 'updateStatus'])->name('complaints.status');

    // Notification polling (AJAX)
    Route::get('/api/new-complaints', [DashboardController::class, 'newComplaints'])->name('api.new-complaints');

    // Analytics
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');

    // Reporter analytics
    Route::get('/reporters', [ReporterController::class, 'index'])->name('reporters.index');

    // User management
    Route::middleware('role:superadmin')->group(function () {
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
        Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    });
});
