# Laravel Email Config

Database-backed email templates with JSON API for CRUD, `{{placeholder}}` replacement, and test sends.

## Requirements

- PHP 8.1+
- Laravel 10, 11, or 12
- `users` table (for `created_by` / `updated_by` foreign keys in the published migration)

## Install

This package is **not on Packagist** by default. You must point Composer at the GitHub repository, then require a **stable tag** or an explicit **dev** constraint.

### Recommended (stable): register VCS, then require `^1.0`

From your Laravel project root:

```bash
composer config repositories.sijanmahato-laravel-email-configuration vcs https://github.com/sijanmahato/laravel-email-configuration.git
composer require sijanmahato/laravel-email-configuration:^1.0
```

That satisfies `minimum-stability: stable` once release tags (for example `v1.0.0`) exist on GitHub.

### Alternative: track `main` as a dev version

```bash
composer config repositories.sijanmahato-laravel-email-configuration vcs https://github.com/sijanmahato/laravel-email-configuration.git
composer require sijanmahato/laravel-email-configuration:dev-main
```

Use this if you have not tagged a release yet, or you always want the latest `main`.

### Manual `composer.json` (VCS)

Merge this into your app’s `composer.json` (keep your existing `require` entries), then run `composer update sijanmahato/laravel-email-configuration`:

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/sijanmahato/laravel-email-configuration.git"
        }
    ],
    "require": {
        "sijanmahato/laravel-email-configuration": "^1.0"
    }
}
```

### Local path package (adjust the path)

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

Then run:

```bash
composer update
php artisan vendor:publish --tag=email-config-config
php artisan vendor:publish --tag=email-config-migrations
php artisan migrate
```

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
