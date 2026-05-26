<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailConfigurationController;

Route::get('/email-configurations', [EmailConfigurationController::class, 'index']);
Route::post('/email-configurations', [EmailConfigurationController::class, 'store']);
Route::get('/email-configurations/{email_configuration}', [EmailConfigurationController::class, 'show']);
Route::put('/email-configurations/{email_configuration}', [EmailConfigurationController::class, 'update']);
Route::delete('/email-configurations/{email_configuration}', [EmailConfigurationController::class, 'destroy']);
Route::post('/email-configurations/{email_configuration}/test-send', [EmailConfigurationController::class, 'testSend']);
