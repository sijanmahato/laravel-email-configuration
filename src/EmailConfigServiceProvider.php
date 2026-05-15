<?php

namespace Karja\EmailConfig;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Karja\EmailConfig\Contracts\UserIdResolver;
use Karja\EmailConfig\Support\DefaultUserIdResolver;

class EmailConfigServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/email-config.php' => config_path('email-config.php'),
        ], 'email-config-config');

        $this->publishes([
            __DIR__.'/../database/migrations/2024_01_01_000000_create_email_configurations_table.php' => database_path('migrations/'.date('Y_m_d_His').'_create_email_configurations_table.php'),
        ], 'email-config-migrations');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'email-config');

        $this->registerRoutes();
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/email-config.php', 'email-config');

        $this->app->singleton(UserIdResolver::class, DefaultUserIdResolver::class);
    }

    protected function registerRoutes(): void
    {
        Route::middleware(config('email-config.middleware', ['api']))
            ->prefix(config('email-config.route_prefix', 'admin/email-configurations'))
            ->group(__DIR__.'/routes/api.php');
    }
}
