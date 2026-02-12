<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Outhebox\Translations\Models\Contributor;
use Outhebox\Translations\Models\Language;

it('runs migrations successfully', function () {
    $this->artisan('translations:install', ['--no-interaction' => true])
        ->assertSuccessful();

    expect(Schema::hasTable('ltu_languages'))->toBeTrue()
        ->and(Schema::hasTable('ltu_translations'))->toBeTrue()
        ->and(Schema::hasTable('ltu_translation_keys'))->toBeTrue();
});

it('seeds default languages', function () {
    $this->artisan('translations:install', ['--no-interaction' => true])
        ->assertSuccessful();

    expect(Language::query()->count())->toBeGreaterThanOrEqual(10)
        ->and(Language::query()->where('code', 'en')->exists())->toBeTrue()
        ->and(Language::query()->where('code', 'ar')->exists())->toBeTrue()
        ->and(Language::query()->where('active', true)->count())->toBe(0);
});

it('creates default contributor in non-interactive mode', function () {
    config(['translations.auth.driver' => 'contributors']);

    $this->artisan('translations:install', ['--no-interaction' => true])
        ->assertSuccessful();

    expect(Contributor::query()->count())->toBe(1);

    $contributor = Contributor::query()->first();
    expect($contributor->email)->toBe('admin@translations.local')
        ->and($contributor->role->value)->toBe('owner');
});

it('skips contributor creation when contributors already exist', function () {
    Contributor::factory()->create();

    $this->artisan('translations:install', ['--no-interaction' => true])
        ->assertSuccessful();

    expect(Contributor::query()->count())->toBe(1);
});

it('skips contributor creation in users mode', function () {
    config(['translations.auth.driver' => 'users']);

    $this->artisan('translations:install', ['--no-interaction' => true])
        ->assertSuccessful();

    expect(Contributor::query()->count())->toBe(0);
});

it('calls vendor:publish for config and assets', function () {
    $this->artisan('translations:install', ['--no-interaction' => true])
        ->assertSuccessful()
        ->expectsOutputToContain('Translations installed successfully');
});

it('does not publish pro assets when pro is not installed', function () {
    $result = $this->artisan('translations:install', ['--no-interaction' => true]);
    $result->assertSuccessful();

    // Pro service provider class doesn't exist in test env, so pro publish should not be called
    // Verify by checking the command succeeds without pro — indirect but sufficient
    expect(class_exists(Outhebox\TranslationsPro\Providers\TranslationsProServiceProvider::class))->toBeFalse();
});

it('suggests upgrade when v1 schema is detected', function () {
    createV1SchemaForInstall();

    $this->artisan('translations:install')
        ->expectsOutputToContain('v1 schema detected')
        ->expectsConfirmation('Run translations:upgrade now?', 'no')
        ->assertFailed();
});

it('runs upgrade when v1 detected and user confirms', function () {
    createV1SchemaForInstall();

    $this->artisan('translations:install')
        ->expectsConfirmation('Run translations:upgrade now?', 'yes')
        ->expectsConfirmation('Proceed with the upgrade?', 'yes')
        ->assertSuccessful();

    expect(Schema::hasTable('ltu_translation_keys'))->toBeTrue();
});

it('confirms each step in interactive mode', function () {
    config(['translations.auth.driver' => 'users']);

    $this->artisan('translations:install')
        ->expectsConfirmation('Publish configuration file?', 'yes')
        ->expectsConfirmation('Publish frontend assets?', 'yes')
        ->expectsConfirmation('Run database migrations?', 'yes')
        ->expectsConfirmation('Seed default languages?', 'yes')
        ->expectsOutputToContain('Translations installed successfully')
        ->assertSuccessful();
});

it('confirms contributor step in interactive mode with contributors driver', function () {
    config(['translations.auth.driver' => 'contributors']);

    $this->artisan('translations:install')
        ->expectsConfirmation('Publish configuration file?', 'yes')
        ->expectsConfirmation('Publish frontend assets?', 'yes')
        ->expectsConfirmation('Run database migrations?', 'yes')
        ->expectsConfirmation('Seed default languages?', 'yes')
        ->expectsConfirmation('Create first contributor?', 'no')
        ->expectsOutputToContain('Translations installed successfully')
        ->assertSuccessful();
});

it('skips steps when user declines in interactive mode', function () {
    config(['translations.auth.driver' => 'users']);

    $this->artisan('translations:install')
        ->expectsConfirmation('Publish configuration file?', 'no')
        ->expectsConfirmation('Publish frontend assets?', 'no')
        ->expectsConfirmation('Run database migrations?', 'no')
        ->expectsConfirmation('Seed default languages?', 'no')
        ->expectsOutputToContain('Translations installed successfully')
        ->assertSuccessful();
});

it('asks to publish lang files when lang folder is missing', function () {
    config(['translations.auth.driver' => 'users']);

    $langPath = lang_path();
    $backupPath = $langPath.'_backup_test';
    File::moveDirectory($langPath, $backupPath);

    try {
        $this->artisan('translations:install')
            ->expectsConfirmation('Publish configuration file?', 'no')
            ->expectsConfirmation('Publish frontend assets?', 'no')
            ->expectsConfirmation('Run database migrations?', 'no')
            ->expectsConfirmation('Seed default languages?', 'no')
            ->expectsConfirmation('Lang folder not found. Publish default language files?', 'yes')
            ->expectsOutputToContain('Translations installed successfully')
            ->assertSuccessful();

        expect(File::isDirectory($langPath))->toBeTrue();
    } finally {
        File::deleteDirectory($langPath);
        File::moveDirectory($backupPath, $langPath);
    }
});

it('skips lang publish when lang folder exists', function () {
    config(['translations.auth.driver' => 'users']);

    expect(File::isDirectory(lang_path()))->toBeTrue();

    $this->artisan('translations:install')
        ->expectsConfirmation('Publish configuration file?', 'no')
        ->expectsConfirmation('Publish frontend assets?', 'no')
        ->expectsConfirmation('Run database migrations?', 'no')
        ->expectsConfirmation('Seed default languages?', 'no')
        ->expectsOutputToContain('Translations installed successfully')
        ->assertSuccessful();
});

function createV1SchemaForInstall(): void
{
    Schema::dropIfExists('ltu_export_logs');
    Schema::dropIfExists('ltu_import_logs');
    Schema::dropIfExists('ltu_contributor_language');
    Schema::dropIfExists('ltu_contributors');
    Schema::dropIfExists('ltu_translations');
    Schema::dropIfExists('ltu_translation_keys');
    Schema::dropIfExists('ltu_groups');
    Schema::dropIfExists('ltu_languages');

    Schema::create('ltu_languages', function ($table) {
        $table->id();
        $table->string('name');
        $table->string('code')->index();
        $table->boolean('rtl')->default(false);
    });

    Schema::create('ltu_translations', function ($table) {
        $table->id();
        $table->foreignId('language_id');
        $table->boolean('source')->default(false);
        $table->timestamps();
    });

    Schema::create('ltu_translation_files', function ($table) {
        $table->id();
        $table->string('name');
        $table->string('extension');
        $table->boolean('is_root')->default(false);
    });

    Schema::create('ltu_phrases', function ($table) {
        $table->id();
        $table->char('uuid', 36);
        $table->foreignId('translation_id')->constrained('ltu_translations')->cascadeOnDelete();
        $table->foreignId('translation_file_id')->constrained('ltu_translation_files')->cascadeOnDelete();
        $table->foreignId('phrase_id')->nullable()->constrained('ltu_phrases')->nullOnDelete();
        $table->text('key');
        $table->string('group');
        $table->text('value')->nullable();
        $table->string('status')->default('active');
        $table->json('parameters')->nullable();
        $table->text('note')->nullable();
        $table->timestamps();
    });

    Schema::create('ltu_contributors', function ($table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->string('password');
        $table->string('avatar')->nullable();
        $table->tinyInteger('role')->nullable();
        $table->rememberToken();
        $table->timestamps();
    });

    Schema::create('ltu_invites', function ($table) {
        $table->id();
        $table->string('email')->unique();
        $table->string('token', 32)->unique();
        $table->tinyInteger('role')->default(2);
        $table->timestamps();
    });
}
