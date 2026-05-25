<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailConfigurationController;

        // ----------------------------------------------------------------
        //  Email Configuration (Templates)
        // ----------------------------------------------------------------
        Route::get('/email-configurations', [EmailConfigurationController::class, 'index'])
            ->name('email-configurations.index');
        Route::get('/email-configurations/{emailConfiguration}', [EmailConfigurationController::class, 'show'])
            ->name('email-configurations.show');
        Route::post('/email-configurations', [EmailConfigurationController::class, 'store'])
            ->name('email-configurations.store');
        Route::put('/email-configurations/{emailConfiguration}', [EmailConfigurationController::class, 'update'])
            ->name('email-configurations.update');
        Route::delete('/email-configurations/{emailConfiguration}', [EmailConfigurationController::class, 'destroy'])
            ->name('email-configurations.destroy');
        Route::post('/email-configurations/{emailConfiguration}/test-send', [EmailConfigurationController::class, 'testSend'])
            ->name('email-configurations.test-send');
