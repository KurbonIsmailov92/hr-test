<?php

use App\Http\Controllers\Careers\CandidateDashboardController;
use App\Http\Controllers\Careers\HomeController;
use Illuminate\Support\Facades\Route;

// Публичный сайт и кабинет кандидата. Каталог вакансий появится на Этапе 3.
Route::get('/', HomeController::class)->name('careers.home');

Route::middleware(['auth', 'permission:candidate.access'])
    ->prefix('candidate')
    ->name('candidate.')
    ->group(function () {
        Route::get('/', CandidateDashboardController::class)->name('dashboard');
    });
