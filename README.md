<p align="center">
    <img src="https://user-images.githubusercontent.com/44909285/201471525-be424567-47a4-495d-a9b1-cd673cff0b23.svg" alt="Logo Laravel-Translations">
</p>

<p align="center">
    <a href="#introduction">Introduction</a> |
    <a href="#installation">Installation</a> |
    <a href="#usage">Usage</a> |
    <a href="#authorization">Authorization</a> |
    <a href="#upgrading">Upgrade</a> |
    <a href="#roadmap">Roadmap</a> |
    <a href="#changelog">Changelog</a>
</p>

<p align="center">
<a href="https://packagist.org/packages/outhebox/laravel-translations"><img src="https://img.shields.io/packagist/v/outhebox/laravel-translations.svg" alt="Packagist"></a>
<a href="https://packagist.org/packages/outhebox/laravel-translations"><img src="https://img.shields.io/packagist/dm/outhebox/laravel-translations.svg" alt="Packagist"></a>
<a href="https://packagist.org/packages/outhebox/laravel-translations"><img src="https://img.shields.io/packagist/php-v/outhebox/laravel-translations.svg" alt="PHP from Packagist"></a>
<a href="https://packagist.org/packages/outhebox/laravel-translations"><img src="https://img.shields.io/badge/Laravel-8.x,%209.x-brightgreen.svg" alt="Laravel Version"></a>
</p>

![Cover](https://user-images.githubusercontent.com/44909285/201598702-5bcd47ed-6202-41a1-af4a-40203b3b76ae.png)

### Introduction

Laravel Translations UI provides a simple way to manage your app translations using a friendly UI, It allows you to add, edit, delete and export translations, and it also provides a search functionality to find translations.

#### Features

- **View & Create & Delete Translations**.
- **Manage** your translations keys.
- **Filter** by translation keys or values,
- and more...

### Installation

You may use the Composer package manager to install Laravel Translations UI into your Laravel project:

```bash
composer require outhebox/laravel-translations
```

After installing Laravel Translations UI, publish its assets using the following commands.

```bash
php artisan translations:install
```

Migrate your database before importing translations, you can use the following command.

```bash
php artisan migrate
```

### Usage

Import your translations using the following command.

```bash
php artisan translations:import
```

If you want to import & overwrite all your previous translations, use the following command.

```bash
php artisan translations:import --fresh
```

Then you can access the translations UI, you can visit the `/translations` in your browser, but first you need to login to your application if it's a production environment,
and you can define who can access the translations UI in the configuration file, see for more details [Authorization](#authorization).

#### Exporting Translations

You can export your translations using the following command.

```bash
php artisan translations:export
```

### Authorization

The Translations UI dashboard may be accessed at the /translations route. By default, you will only be able to access this dashboard in the local environment. Within your app/Providers/TranslationsServiceProvider.php file, there is an authorization gate definition. 
This authorization gate controls access to Translations UI Dashboard in non-local environments. You are free to modify this gate as needed to restrict access to your Translations UI installation:

```php
protected function gate()
{
    Gate::define('viewLaravelTranslationsUI', function ($user) {
        return in_array($user->email, [
            //
        ]);
    });
}
```

### Upgrading

When upgrading to a new major version of Laravel Translations UI, it's important that you carefully review the upgrade guide.

In addition, when upgrading to any new Translations UI version, you should re-publish Translations UI assets:

```bash
php artisan translations:publish
```

To keep the assets up-to-date and avoid issues in future updates, you may add the translations:publish command to the post-update-cmd scripts in your application's composer.json file:

```json
{
    "scripts": {
        "post-update-cmd": [
            "@php artisan translations:publish --ansi"
        ]
    }
}
```

### Roadmap
- [ ] Add tests.
- [ ] Improve the UI.
- [ ] Vendor translations support.
- [ ] Google Translate API integration.
- [ ] Invite collaborators to manage translations.
- [ ] Add revision history.
- [ ] Add more features.

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

### Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Support

Hey 👋 My name is Mohamed Ashraf, I'm a full-stack web developer, if you like my work, you can buy me a coffee to keep me awake and coding, or sponsoring me, at least make sure you're supporting me by starring ⭐ the repository.

<a href="https://www.buymeacoffee.com/outhebox" target="_blank"><img src="https://cdn.buymeacoffee.com/buttons/default-orange.png" alt="Buy Me A Coffee" style="height: 51px !important;width: 217px !important;" ></a>

## Credits

- [Mohamed Ashraf](https://github.com/MohmmedAshraf)
- [All Contributors](../../contributors)

### License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
