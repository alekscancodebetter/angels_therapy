<?php

use App\Http\Controllers\Admin\AdminSessionController;
use App\Http\Controllers\Admin\AdminSetupController;
use App\Http\Controllers\Admin\AppointmentConflictController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\AppointmentAvailabilityController;
use App\Http\Controllers\AppointmentRequestController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TreatmentController;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');
Route::view('/booking-confirmation', 'booking-confirmation')->name('booking-confirmation');
Route::view('/privacy-policy', 'privacy-policy')->name('privacy-policy');
Route::view('/terms-and-conditions', 'terms-and-conditions')->name('terms-and-conditions');
Route::get('/sitemap.xml', function (): Response {
    return response()
        ->view('sitemap', ['treatments' => config('treatments')])
        ->header('Content-Type', 'application/xml');
})->name('sitemap');
Route::get('/treatments/{slug}', [TreatmentController::class, 'show'])
    ->name('treatments.show');
Route::post('/appointment-request', [AppointmentRequestController::class, 'store'])
    ->middleware('throttle:6,1')
    ->name('appointment-request.store');
Route::get('/appointments/availability', AppointmentAvailabilityController::class)
    ->middleware('throttle:60,1')
    ->name('appointments.availability');
Route::post('/bookings', [BookingController::class, 'store'])
    ->middleware('throttle:6,1')
    ->name('bookings.store');

Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::middleware('guest')->group(function (): void {
        Route::get('/setup', [AdminSetupController::class, 'create'])->name('setup');
        Route::post('/setup', [AdminSetupController::class, 'store'])->middleware('throttle:5,1')->name('setup.store');
        Route::get('/login', [AdminSessionController::class, 'create'])->name('login');
        Route::post('/login', [AdminSessionController::class, 'store'])->name('login.store');
    });

    Route::middleware(['auth', 'admin'])->group(function (): void {
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::get('/calendar', CalendarController::class)->name('calendar');
        Route::resource('customers', CustomerController::class)->except(['destroy']);
        Route::get('/appointments/conflict', AppointmentConflictController::class)->name('appointments.conflict');
        Route::resource('appointments', AdminAppointmentController::class)->only(['index', 'create', 'store', 'edit', 'update']);
        Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
        Route::patch('/settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::post('/logout', [AdminSessionController::class, 'destroy'])->name('logout');
    });
});
