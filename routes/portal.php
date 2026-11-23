<?php

use App\Http\Controllers\Portal\DashboardController;
use Illuminate\Support\Facades\Route;

// Employee Portal: личный кабинет сотрудника. Наполнение на Этапе 6.
Route::middleware(['auth', 'permission:portal.access'])
    ->prefix('portal')
    ->name('portal.')
    ->group(function () {
        Route::get('/', DashboardController::class)->name('dashboard');
    });
