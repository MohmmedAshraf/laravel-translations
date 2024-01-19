<?php

namespace Outhebox\TranslationsUI\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

// clean old version
class CleanOldVersionCommand extends Command
{
    public $signature = 'translations:clean';

    public $description = 'Clean all of the Translations UI resources and database tables from any version before v1.0';

    public function handle(): int
    {
        // clean assets
        File::deleteDirectory(public_path('vendor/translations-ui'));

        // clean database tables
        Schema::withoutForeignKeyConstraints(function () {
            Schema::dropIfExists('ltu_languages');
            Schema::dropIfExists('ltu_phrases');
            Schema::dropIfExists('ltu_translations');
            Schema::dropIfExists('ltu_translation_files');
        });

        // clean migrations
        if (Schema::hasTable('migrations')) {
            DB::table('migrations')->where('migration', 'like', '%create_translations_tables%')->delete();
        }

        // remove old config file
        File::delete(config_path('translations.php'));

        // remove old service provider
        $this->unregisterServiceProvider();

        $this->info('Old version of Translations UI was cleaned successfully.');

        return self::SUCCESS;
    }

    protected function unregisterServiceProvider(): void
    {
        $namespace = Str::replaceLast('\\', '', $this->laravel->getNamespace());

        $appConfig = file_get_contents(config_path('app.php'));

        if (Str::contains($appConfig, $namespace.'\\Providers\\TranslationsServiceProvider::class')) {
            file_put_contents(config_path('app.php'), str_replace(
                "$namespace\\Providers\\TranslationsServiceProvider::class,".PHP_EOL,
                '',
                $appConfig
            ));
        }
    }
}
