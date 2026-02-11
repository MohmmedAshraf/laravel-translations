<?php

use Outhebox\Translations\Support\LanguageDataProvider;

it('returns all languages', function () {
    $languages = LanguageDataProvider::all();

    expect($languages)->toBeArray()
        ->and(count($languages))->toBeGreaterThan(50);
});

it('each language has required keys', function () {
    $languages = LanguageDataProvider::all();

    foreach ($languages as $language) {
        expect($language)->toHaveKeys(['code', 'name', 'native_name', 'rtl', 'is_source', 'active']);
    }
});

it('finds a language by code', function () {
    $english = LanguageDataProvider::findByCode('en');

    expect($english)->not->toBeNull()
        ->and($english['name'])->toBe('English')
        ->and($english['native_name'])->toBe('English')
        ->and($english['rtl'])->toBeFalse();
});

it('finds an rtl language by code', function () {
    $arabic = LanguageDataProvider::findByCode('ar');

    expect($arabic)->not->toBeNull()
        ->and($arabic['name'])->toBe('Arabic')
        ->and($arabic['rtl'])->toBeTrue();
});

it('returns null for unknown code', function () {
    expect(LanguageDataProvider::findByCode('xxx'))->toBeNull();
});

it('all languages default to inactive and not source', function () {
    $languages = LanguageDataProvider::all();

    foreach ($languages as $language) {
        expect($language['active'])->toBeFalse()
            ->and($language['is_source'])->toBeFalse();
    }
});

it('has unique language codes', function () {
    $languages = LanguageDataProvider::all();
    $codes = array_column($languages, 'code');

    expect(count($codes))->toBe(count(array_unique($codes)));
});
