<?php

use Outhebox\Translations\Enums\TranslationStatus;
use Outhebox\Translations\Models\Contributor;
use Outhebox\Translations\Models\Group;
use Outhebox\Translations\Models\Language;
use Outhebox\Translations\Models\Translation;
use Outhebox\Translations\Models\TranslationKey;

it('boots the service provider', function () {
    expect(app('translations.auth'))->not->toBeNull();
});

it('loads routes', function () {
    $routes = collect(app('router')->getRoutes()->getRoutes())
        ->pluck('action.as')
        ->filter()
        ->all();

    expect($routes)->toContain('ltu.languages.index')
        ->and($routes)->toContain('ltu.phrases.index')
        ->and($routes)->toContain('ltu.source.index')
        ->and($routes)->toContain('ltu.groups.index');
});

it('runs migrations', function () {
    expect(Language::query()->count())->toBe(0)
        ->and(Group::query()->count())->toBe(0)
        ->and(TranslationKey::query()->count())->toBe(0)
        ->and(Translation::query()->count())->toBe(0);
});

it('creates models with factories', function () {
    $language = Language::factory()->create();
    $group = Group::factory()->create();
    $key = TranslationKey::factory()->for($group)->create();
    $translation = Translation::factory()->for($key)->for($language)->create();

    expect($language)->toBeInstanceOf(Language::class)
        ->and($group)->toBeInstanceOf(Group::class)
        ->and($key)->toBeInstanceOf(TranslationKey::class)
        ->and($translation)->toBeInstanceOf(Translation::class);
});

it('creates contributors with factory', function () {
    $contributor = Contributor::factory()->owner()->create();

    expect($contributor)->toBeInstanceOf(Contributor::class)
        ->and($contributor->role->value)->toBe('owner');
});

it('casts translation status', function () {
    $language = Language::factory()->create();
    $group = Group::factory()->create();
    $key = TranslationKey::factory()->for($group)->create();
    $translation = Translation::factory()->for($key)->for($language)->create([
        'status' => TranslationStatus::Translated,
    ]);

    expect($translation->status)->toBe(TranslationStatus::Translated);
});
