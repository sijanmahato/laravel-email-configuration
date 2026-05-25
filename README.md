# Laravel Email Config

Database-backed email templates with JSON API for CRUD, `{{placeholder}}` replacement, and test sends.

This repository is **application code** (standard `App\` namespaces), not a Composer package. Copy or merge the folders into your Laravel project.

## Requirements

- PHP 8.1+
- Laravel 10, 11, or 12
- `users` table (for `created_by` / `updated_by` foreign keys in the migration)

## Install into your Laravel app

Copy these paths into your project (merge if you already have files with the same names):

| This repo | Your Laravel app |
|-----------|------------------|
| `app/` | `app/` |
| `routes/email-config-api.php` | `routes/email-config-api.php` |
| `config/email-config.php` | `config/email-config.php` |
| `database/migrations/` | `database/migrations/` |
| `resources/views/emails/` | `resources/views/emails/` |

Register the provider in `bootstrap/providers.php` (Laravel 11+) or `config/app.php` (Laravel 10):

```php
App\Providers\EmailConfigServiceProvider::class,
```

Run migrations:

```bash
php artisan migrate
```

## Configuration

Edit `config/email-config.php`:

- **`route_prefix`**: URL segment for the routes. If your API already uses an `api` prefix, set this to `api/admin/email-configurations`.
- **`middleware`**: Stack applied to all routes. Replace `auth:sanctum` with `auth:api` or add permission middleware as needed.

## Auditing

The `EmailConfiguration` model dispatches:

- `App\Events\EmailConfigurationCreated`
- `App\Events\EmailConfigurationUpdated`
- `App\Events\EmailConfigurationDeleted`

Subscribe in your `EventServiceProvider` (or `AppServiceProvider`) and forward them to your audit logger.

## Custom user id for `created_by` / `updated_by`

Bind your own resolver in `AppServiceProvider`:

```php
use App\Contracts\UserIdResolver;
use Illuminate\Support\ServiceProvider;

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

## Tests

From this repo (after `composer install`):

```bash
vendor/bin/phpunit
```

## License

MIT.
