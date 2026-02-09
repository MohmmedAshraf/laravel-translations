<?php

use Illuminate\Support\Facades\Schema;

it('runs successfully', function () {
    $this->artisan('translations:update', ['--no-interaction' => true])
        ->assertSuccessful();
});

it('runs migrations', function () {
    $this->artisan('translations:update', ['--no-interaction' => true])
        ->assertSuccessful();

    expect(Schema::hasTable('ltu_languages'))->toBeTrue()
        ->and(Schema::hasTable('ltu_translations'))->toBeTrue()
        ->and(Schema::hasTable('ltu_translation_keys'))->toBeTrue();
});

it('completes with success message', function () {
    $this->artisan('translations:update', ['--no-interaction' => true])
        ->assertSuccessful()
        ->expectsOutputToContain('Translations updated successfully');
});

it('does not publish pro assets when pro is not installed', function () {
    $this->artisan('translations:update', ['--no-interaction' => true])
        ->assertSuccessful();

    expect(class_exists(Outhebox\TranslationsPro\Providers\TranslationsProServiceProvider::class))->toBeFalse();
});
