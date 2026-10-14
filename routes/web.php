<?php

use App\Http\Controllers\AppointmentRequestController;
use App\Http\Controllers\TreatmentController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');
Route::get('/treatments/{slug}', [TreatmentController::class, 'show'])
    ->name('treatments.show');
Route::post('/appointment-request', [AppointmentRequestController::class, 'store'])
    ->middleware('throttle:6,1')
    ->name('appointment-request.store');
