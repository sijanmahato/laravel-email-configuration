<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Route prefix
    |--------------------------------------------------------------------------
    |
    | Registered under your application's route domain. If your API lives
    | under /api, set this to "api/admin/email-configurations".
    |
    */
    'route_prefix' => 'admin/email-configurations',

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    |
    | Applied to all package routes. Example:
    | ['api', 'auth:api', 'permission:manage-email-configurations']
    |
    */
    'middleware' => [
        'api',
        'auth:api',
    ],

];
