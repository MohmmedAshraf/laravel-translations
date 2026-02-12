<?php

use Illuminate\Support\Facades\Schema;
use Outhebox\Translations\Models\Contributor;
use Outhebox\Translations\Models\TranslationKey;

it('runs import when --import flag is passed', function () {
    config(['translations.lang_path' => __DIR__.'/../../Fixtures/lang']);

    $this->artisan('translations:install', [
        '--no-interaction' => true,
        '--import' => true,
    ])->assertSuccessful();

    expect(TranslationKey::query()->count())->toBeGreaterThan(0);
});

it('does not import when --import flag is not passed', function () {
    $this->artisan('translations:install', [
        '--no-interaction' => true,
    ])->assertSuccessful();

    expect(TranslationKey::query()->count())->toBe(0);
});

it('skips contributor creation when contributors already exist with contributor driver', function () {
    config(['translations.auth.driver' => 'contributors']);
    Contributor::factory()->create();

    $this->artisan('translations:install', [
        '--no-interaction' => true,
    ])->assertSuccessful();

    expect(Contributor::query()->count())->toBe(1);
});

it('runs upgrade automatically when v1 detected with --no-interaction', function () {
    createV1SchemaForExtendedInstall();

    $this->artisan('translations:install', ['--no-interaction' => true])
        ->expectsOutputToContain('v1 schema detected')
        ->assertSuccessful();

    expect(Schema::hasTable('ltu_translation_keys'))->toBeTrue();
});

function createV1SchemaForExtendedInstall(): void
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
