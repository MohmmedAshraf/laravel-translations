<?php

use Outhebox\Translations\Enums\LanguageStatus;
use Outhebox\Translations\Enums\TranslationStatus;
use Outhebox\Translations\Support\DataTable\Filter;

it('creates a filter with make', function () {
    $filter = Filter::make('status');

    expect($filter->getKey())->toBe('status');
});

it('sets label', function () {
    $array = Filter::make('status')
        ->label('Translation Status')
        ->toArray();

    expect($array['label'])->toBe('Translation Status');
});

it('auto-generates label from key when not set', function () {
    $array = Filter::make('created_at')->toArray();

    expect($array['label'])->toBe('Created At');
});

it('sets icon', function () {
    $array = Filter::make('status')
        ->icon('filter')
        ->toArray();

    expect($array['icon'])->toBe('filter');
});

it('sets searchable', function () {
    $array = Filter::make('language')
        ->searchable()
        ->toArray();

    expect($array['searchable'])->toBeTrue();
});

it('defaults searchable to false', function () {
    $array = Filter::make('status')->toArray();

    expect($array['searchable'])->toBeFalse();
});

it('sets manual options', function () {
    $options = [
        ['value' => 'active', 'label' => 'Active'],
        ['value' => 'inactive', 'label' => 'Inactive'],
    ];

    $array = Filter::make('status')
        ->options($options)
        ->toArray();

    expect($array['options'])->toBe($options);
});

it('builds options from enum', function () {
    $array = Filter::make('status', TranslationStatus::class)->toArray();

    expect($array['options'])->toHaveCount(4)
        ->and($array['options'][0]['value'])->toBe('untranslated')
        ->and($array['options'][0]['label'])->toBe('Untranslated')
        ->and($array['options'][1]['value'])->toBe('translated')
        ->and($array['options'][1]['label'])->toBe('Translated');
});

it('includes icons from enum implementing HasIcon', function () {
    $array = Filter::make('status', TranslationStatus::class)->toArray();

    expect($array['options'][0]['icon'])->toBe('translate')
        ->and($array['options'][2]['icon'])->toBe('alert-circle');
});

it('includes icon colors from enum implementing HasColor', function () {
    $array = Filter::make('status', LanguageStatus::class)->toArray();

    expect($array['options'][0]['iconColor'])->toBe('green')
        ->and($array['options'][1]['iconColor'])->toBe('blue')
        ->and($array['options'][2]['iconColor'])->toBe('amber')
        ->and($array['options'][3]['iconColor'])->toBe('neutral');
});

it('converts to array with all fields', function () {
    $array = Filter::make('status')
        ->label('Status')
        ->icon('filter')
        ->searchable()
        ->toArray();

    expect($array)->toHaveKeys(['key', 'label', 'icon', 'options', 'searchable'])
        ->and($array['key'])->toBe('status')
        ->and($array['label'])->toBe('Status')
        ->and($array['icon'])->toBe('filter')
        ->and($array['searchable'])->toBeTrue();
});
