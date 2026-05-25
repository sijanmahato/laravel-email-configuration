# Laravel Email Config

Database-backed email templates with JSON API for CRUD, `{{placeholder}}` replacement, and test sends.

## Requirements

- PHP 8.1+
- Laravel 10, 11, or 12
- `users` table (for `created_by` / `updated_by` foreign keys in the published migration)

## Install

Published on Packagist as **[sijanmahato/laravel-email-configuration](https://packagist.org/packages/sijanmahato/laravel-email-configuration)**.

### From Packagist (recommended)

```bash
composer require sijanmahato/laravel-email-configuration
```

Use `^1.0` (or another semver range) so Composer respects `minimum-stability: stable` when you ship tagged releases.

### From GitHub (VCS, optional)

Use this if you need a branch that is not on Packagist yet, or you are testing a fork:

```bash
composer config repositories.sijanmahato-laravel-email-configuration vcs https://github.com/sijanmahato/laravel-email-configuration.git
composer require sijanmahato/laravel-email-configuration:dev-main
```

### Local path package (adjust the path)

Add to your app `composer.json`:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "./packages/laravel-email-configuration",
            "options": { "symlink": true }
        }
    ],
    "require": {
        "sijanmahato/laravel-email-configuration": "*"
    }
}
```

Then run `composer update`.

### After the package is installed

```bash
php artisan vendor:publish --tag=email-config-config
php artisan vendor:publish --tag=email-config-migrations
php artisan migrate
php artisan vendor:publish --tag=email-config-api
php artisan vendor:publish --tag=email-config-model
php artisan vendor:publish --tag=email-config-controller
```

The additional publish commands will copy the package's API route, model, and controller into your application for customization:

- `--tag=email-config-api` → `routes/email-config-api.php`
- `--tag=email-config-model` → `app/Models/EmailConfiguration.php`
- `--tag=email-config-controller` → `app/Http/Controllers/EmailConfigurationController.php`

## Configuration

Edit `config/email-config.php`:

- **`route_prefix`**: URL segment for the package routes. If your HTTP kernel already prefixes API routes with `api`, set this to `api/admin/email-configurations` (or whatever matches your app).
- **`middleware`**: Stack applied to all routes. Replace `auth:sanctum` with `auth:api` or add permission middleware as needed.

## Auditing

This package does not ship your application’s `Auditable` trait. Instead, the `EmailConfiguration` model dispatches:

- `Karja\EmailConfig\Events\EmailConfigurationCreated`
- `Karja\EmailConfig\Events\EmailConfigurationUpdated`
- `Karja\EmailConfig\Events\EmailConfigurationDeleted`

Subscribe in your `EventServiceProvider` (or `AppServiceProvider`) and forward them to your audit logger.

## Custom user id for `created_by` / `updated_by`

Bind your own resolver in a service provider:

```php
use Illuminate\Support\ServiceProvider;
use Karja\EmailConfig\Contracts\UserIdResolver;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(UserIdResolver::class, function () {
            return new class implements UserIdResolver {
                public function resolve(): ?int
                {
                    return auth()->id();
                }
            };
        });
    }
}
```

## API

| Method | Path | Action |
|--------|------|--------|
| GET | `/{prefix}` | List templates |
| GET | `/{prefix}/{id}` | Show one |
| POST | `/{prefix}` | Create |
| PUT | `/{prefix}/{id}` | Update |
| DELETE | `/{prefix}/{id}` | Delete |
| POST | `/{prefix}/{id}/test-send` | Send test email |

Test send JSON body: `{ "to": "user@example.com", "variables": { "user_name": "Jane" } }`.

Placeholders use `{{variable_name}}` in subject, HTML, and text bodies.

## License

MIT.
