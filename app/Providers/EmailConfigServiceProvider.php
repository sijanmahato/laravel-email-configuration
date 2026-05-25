<?php

namespace App\Providers;

use App\Contracts\UserIdResolver;
use App\Support\DefaultUserIdResolver;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class EmailConfigServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerRoutes();
    }

    public function register(): void
    {
        $this->app->singleton(UserIdResolver::class, DefaultUserIdResolver::class);
    }

    protected function registerRoutes(): void
    {
        Route::middleware(config('email-config.middleware', ['api']))
            ->prefix(config('email-config.route_prefix', 'admin/email-configurations'))
            ->group(base_path('routes/email-config-api.php'));
    }
}
