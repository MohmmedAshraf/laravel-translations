![Cover](art/cover.png)

<p align="center">
    <a href="#installation">Installation</a> |
    <a href="#usage">Usage</a> |
    <a href="#configuration">Configuration</a> |
    <a href="#updating">Updating</a> |
    <a href="#upgrading-from-v1">Upgrading from v1</a> |
    <a href="#contributing">Contributing</a>
</p>

<p align="center">
<a href="https://packagist.org/packages/outhebox/laravel-translations"><img src="https://img.shields.io/packagist/v/outhebox/laravel-translations" alt="Latest Stable Version"></a>
<a href="https://github.com/MohmmedAshraf/laravel-translations/actions?query=workflow%3Arun-tests"><img src="https://github.com/MohmmedAshraf/laravel-translations/workflows/run-tests/badge.svg" alt="Tests"></a>
<a href="https://packagist.org/packages/outhebox/laravel-translations"><img src="https://img.shields.io/packagist/dt/outhebox/laravel-translations" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/outhebox/laravel-translations"><img src="https://img.shields.io/packagist/php-v/outhebox/laravel-translations.svg" alt="PHP from Packagist"></a>
<a href="https://packagist.org/packages/outhebox/laravel-translations"><img src="https://img.shields.io/badge/Laravel-11.x%20|%2012.x-brightgreen.svg" alt="Laravel Version"></a>
</p>

## Introduction

Laravel Translations provides a beautiful UI for managing your application's translations. Import, edit, search, and export translations — all from a clean dashboard. No npm or Vite required in your project.

<p align="center">
    <a href="https://outhebox.dev/products/laravel-translations-ui-pro">
        <img src="art/demo.gif" alt="Laravel Translations Demo" width="100%" />
    </a>
</p>

> Looking for AI-powered translations, revision history, and team collaboration? Check out [Laravel Translations Pro](https://outhebox.dev/products/laravel-translations-ui-pro).

## Installation

**Requirements:** PHP 8.3+ and Laravel 11.x or 12.x

```bash
composer require outhebox/laravel-translations
```

Run the install command to publish assets, config, and migrations:

```bash
php artisan translations:install
```

Run migrations:

```bash
php artisan migrate
```

Visit `/translations` in your browser.

## Usage

### Importing Translations

```bash
php artisan translations:import
```

To overwrite existing translations:

```bash
php artisan translations:import --fresh
```

### Exporting Translations

Export from the UI or via command:

```bash
php artisan translations:export
```

### Check Status

```bash
php artisan translations:status
```

## Configuration

Publish the config file:

```bash
php artisan vendor:publish --tag=translations-config
```

This publishes `config/translations.php` where you can configure the path, middleware, authentication, source language, import/export settings, and more.

## Updating

After updating the package, re-publish the assets:

```bash
php artisan translations:update
```

You can automate this in your `composer.json`:

```json
{
    "scripts": {
        "post-update-cmd": ["@php artisan translations:update --ansi"]
    }
}
```

## Upgrading from v1

<details>
<summary>v2 is a full rewrite with a new frontend (React/Inertia), new database structure, and updated namespace. Click to expand upgrade instructions.</summary>

### Steps

1. **Update the package:**

```bash
composer require outhebox/laravel-translations:^2.0
```

2. **Run the upgrade command** to migrate your v1 data:

```bash
php artisan translations:upgrade
```

This will detect your v1 tables, migrate languages, groups, keys, and translations to the new structure.

3. **Clean up old tables** (optional):

```bash
php artisan translations:upgrade --cleanup
```

4. **Publish the new assets:**

```bash
php artisan translations:install
```

### Breaking Changes

- **Namespace:** `Outhebox\TranslationsUI` is now `Outhebox\Translations`
- **Frontend:** Vue has been replaced with React (no action needed — assets are pre-compiled)
- **Database:** New table structure — run the upgrade command above
- **Config:** New structure — re-publish with `--tag=translations-config`

</details>

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Mohamed Ashraf](https://github.com/MohmmedAshraf)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
