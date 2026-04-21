<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TamuController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

Route::get('/', [TamuController::class, 'index'])->name('tamu.form');
Route::post('/tamu', [TamuController::class, 'store'])->name('tamu.store');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', ])
    ->name('dashboard');

Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])
    ->middleware(['auth', ])
    ->name('dashboard.chartdata');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/tamu', [TamuController::class, 'indexAdmin'])->name('tamu.index');
    Route::get('/admin/tamu/{id}/edit', [TamuController::class, 'edit'])->name('tamu.edit');
    Route::put('/admin/tamu/{id}', [TamuController::class, 'update'])->name('tamu.update');
    Route::patch('/admin/tamu/{id}/selesai', [TamuController::class, 'markSelesai'])->name('tamu.selesai');
    Route::delete('/admin/tamu/{id}', [TamuController::class, 'destroy'])->name('tamu.destroy');
    Route::patch('/admin/tamu/{id}/nomor-diklik', [TamuController::class, 'markNomorDiklik'])->name('tamu.nomor-diklik');
    Route::get('/admin/tamu/export-pdf', [TamuController::class, 'exportPDF'])->name('tamu.export.pdf');
});

require __DIR__.'/auth.php';