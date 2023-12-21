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
<a href="https://packagist.org/packages/outhebox/laravel-translations"><img src="https://img.shields.io/packagist/v/outhebox/laravel-translations" alt="Latest Stable Version"></a>
<a href="https://github.com/MohmmedAshraf/laravel-translations/actions?query=workflow%3Arun-tests"><img src="https://github.com/MohmmedAshraf/laravel-translations/workflows/run-tests/badge.svg" alt="Tests"></a>
<a href="https://packagist.org/packages/outhebox/laravel-translations"><img src="https://img.shields.io/packagist/dt/outhebox/laravel-translations" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/outhebox/laravel-translations"><img src="https://img.shields.io/packagist/php-v/outhebox/laravel-translations.svg" alt="PHP from Packagist"></a>
<a href="https://packagist.org/packages/outhebox/laravel-translations"><img src="https://img.shields.io/badge/Laravel-10.x-brightgreen.svg" alt="Laravel Version"></a>
</p>

![Cover](https://user-images.githubusercontent.com/44909285/201598702-5bcd47ed-6202-41a1-af4a-40203b3b76ae.png)

### Introduction

Laravel Translations UI is a package that provides a simple and friendly user interface for managing translations in a Laravel app. With this package, you can easily add, edit, delete, and export translations, and use the search function to find specific translations.

> 📺 **[Watch a 4-minute video by Povilas Korop](https://www.youtube.com/watch?v=lYkgXnwnVbw)** showcasing the package.

### Requirements

- PHP 8.1 or higher
- Laravel 10.x or higher

#### Features

- View, create, and delete translations
- Manage translation keys
- Filter by translation keys or values
- Import and export translations
- Search function to find specific translations
- and more...

### Installation

To install Laravel Translations UI in your Laravel project, run the following command:

```bash
composer require outhebox/laravel-translations
```

Before you can import translations, you'll need to migrate your database. Run the following command to do so:

```bash
php artisan migrate
```

After installing the package, you'll need to publish its assets by running the following command:

```bash
php artisan translations:install
```

### Usage

To import your translations, run the following command:

```bash
php artisan translations:import
```

To import and overwrite all previous translations, use the following command:

```bash
php artisan translations:import --fresh
```

To access the translations UI, visit /translations in your browser. If you are using a production environment, you will need to login to your application before accessing the translations UI. 

You can customize the authorization gate in the configuration file to control access to the translations UI in non-local environments. For more information, see for more details [Authorization](#authorization).

#### Exporting Translations

To export your translations, run the following command:

```bash
php artisan translations:export
```

### Authorization

By default, the Translations UI dashboard can only be accessed in the local environment. The authorization gate in the `app/Providers/TranslationsServiceProvider.php` file controls access to the Translations UI dashboard in non-local environments. You can modify this gate as needed to restrict access to your Translations UI installation.

To customize the authorization gate, you can define a closure in the gate method of the TranslationsServiceProvider class:

```php
protected function gate()
{
    Gate::define('viewLaravelTranslationsUI', function ($user) {
        return in_array($user->email, [
            // return true or false based on your authorization logic
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

---
### Support

Thank you for considering supporting the development of this package! If you'd like to contribute, you can buy me a coffee or sponsor me to help keep me motivated to continue improving this package. You can also support the project by starring ⭐ the repository.

To buy me a coffee, click the button below:

<a href="https://www.buymeacoffee.com/outhebox" target="_blank"><img src="https://cdn.buymeacoffee.com/buttons/default-orange.png" alt="Buy Me A Coffee" style="height: 51px !important;width: 217px !important;" ></a>


## Credits

- [Mohamed Ashraf](https://github.com/MohmmedAshraf)
- [All Contributors](../../contributors)

### License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
