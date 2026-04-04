<?php

// appointments
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentController;

Route::prefix('appointments')->group(function() {
    Route::get('/',[AppointmentController::class,'index']);
    Route::get('/today',[AppointmentController::class,'today']);
    Route::get('/{appointment}',[AppointmentController::class,'show']);
});