<?php

use App\Http\Controllers\EmailConfigurationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [EmailConfigurationController::class, 'index']);
Route::post('/', [EmailConfigurationController::class, 'store']);
Route::get('{email_configuration}', [EmailConfigurationController::class, 'show']);
Route::put('{email_configuration}', [EmailConfigurationController::class, 'update']);
Route::delete('{email_configuration}', [EmailConfigurationController::class, 'destroy']);
Route::post('{email_configuration}/test-send', [EmailConfigurationController::class, 'testSend']);
