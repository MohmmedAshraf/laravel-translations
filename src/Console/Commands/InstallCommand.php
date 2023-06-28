<?php

namespace Outhebox\LaravelTranslations\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Outhebox\LaravelTranslations\Database\Seeders\LanguagesTableSeeder;

class InstallCommand extends Command
{
    public $signature = 'translations:install';

    public $description = 'Install Laravel Translations UI package and publish its assets';

    public function handle(): void
    {
        $this->comment('Publishing Laravel Translations UI Service Provider...');
        $this->callSilent('vendor:publish', ['--tag' => 'translations-provider']);

        $this->comment('Publishing Laravel Translations UI Assets...');
        $this->callSilent('vendor:publish', ['--tag' => 'translations-assets']);

        $this->comment('Publishing Laravel Translations UI Configuration...');
        $this->callSilent('vendor:publish', ['--tag' => 'translations-config']);

        $this->comment('Installing Laravel Translations UI Default Languages...');
        $this->callSilent('db:seed', ['--class' => LanguagesTableSeeder::class]);

        $this->registerServiceProvider();

        $this->info('Laravel Translations UI scaffolding installed successfully.');
    }

    protected function registerServiceProvider(): void
    {
        $namespace = Str::replaceLast('\\', '', $this->laravel->getNamespace());

        $appConfig = file_get_contents(config_path('app.php'));

        if (Str::contains($appConfig, $namespace.'\\Providers\\TranslationsServiceProvider::class')) {
            return;
        }

        file_put_contents(config_path('app.php'), str_replace(
            "$namespace\\Providers\EventServiceProvider::class,".PHP_EOL,
            "$namespace\\Providers\EventServiceProvider::class,".PHP_EOL."        $namespace\Providers\TranslationsServiceProvider::class,".PHP_EOL,
            $appConfig
        ));

        file_put_contents(app_path('Providers/TranslationsServiceProvider.php'), str_replace(
            "namespace App\Providers;",
            "namespace $namespace\Providers;",
            file_get_contents(app_path('Providers/TranslationsServiceProvider.php'))
        ));
    }
}
