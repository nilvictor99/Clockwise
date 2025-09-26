<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PasswordShareController;
use App\Http\Controllers\PasswordVaultController;
use App\Http\Controllers\TimesheetController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard');
        Route::get('/dashboard/show', 'show')->name('dashboard.show');
        Route::post('/dashboard/uploadphoto', 'uploadbg')->name('dashboard.uploadbg');
    });

    Route::controller(TimesheetController::class)->group(function () {
        Route::get('/timesheet', 'index')->name('timesheet');
        Route::get('/timesheets/list', 'list')->name('timesheets.list');
        Route::get('/timesheet/qr-code', 'generate')->name('generate');
        Route::post('/timesheets/qr-scan', 'scan')->name('scan');
        Route::get('/timesheets/{id}/edit', 'edit')->name('timesheets.edit');
        Route::put('/timesheets/{id}/update', 'update')->name('timesheets.update');
    });

    Route::controller(PasswordVaultController::class)->group(function () {
        Route::get('/password-vault', 'index')->name('password-vault');
        Route::get('/password-vault/list', 'list')->name('password-vault.list');
        Route::get('/password-vault/create', 'create')->name('password-vault.create');
        Route::get('/generate-password', 'generate')->name('generate-password');
        Route::post('/password-vault/store', 'store')->name('password-vault.store');
        Route::get('/password-vault/{id}/edit', 'edit')->name('password-vault.edit');
        Route::put('/password-vault/{id}/update', 'update')->name('password-vault.update');
        Route::delete('/password-vault/{id}/delete', 'destroy')->name('password-vault.destroy');
    });

    Route::controller(PasswordShareController::class)->group(function () {
        Route::get('/password-share', 'index')->name('password-share');
        Route::get('/password-share/list', 'list')->name('password-share.list');
        Route::post('/password-share/store', 'store')->name('password-share.store');
        Route::get('/password-share/{id}/edit', 'edit')->name('password-share.edit');
        Route::put('/password-share/{id}/update', 'update')->name('password-share.update');
        Route::delete('/password-share/{id}/delete', 'destroy')->name('password-share.destroy');
    });
});
