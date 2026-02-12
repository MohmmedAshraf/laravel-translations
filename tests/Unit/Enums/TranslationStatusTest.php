<?php

use Outhebox\Translations\Enums\TranslationStatus;

it('has four statuses', function () {
    expect(TranslationStatus::cases())->toHaveCount(4);
});

it('has correct labels', function () {
    expect(TranslationStatus::Untranslated->getLabel())->toBe('Untranslated')
        ->and(TranslationStatus::Translated->getLabel())->toBe('Translated')
        ->and(TranslationStatus::NeedsReview->getLabel())->toBe('Needs Review')
        ->and(TranslationStatus::Approved->getLabel())->toBe('Approved');
});

it('has correct colors', function () {
    expect(TranslationStatus::Untranslated->getColor())->toBe('neutral')
        ->and(TranslationStatus::Translated->getColor())->toBe('green')
        ->and(TranslationStatus::NeedsReview->getColor())->toBe('amber')
        ->and(TranslationStatus::Approved->getColor())->toBe('blue');
});

it('has correct icons', function () {
    expect(TranslationStatus::Untranslated->getIcon())->toBe('translate')
        ->and(TranslationStatus::Translated->getIcon())->toBe('translate')
        ->and(TranslationStatus::NeedsReview->getIcon())->toBe('alert-circle')
        ->and(TranslationStatus::Approved->getIcon())->toBe('check');
});

it('has correct string values', function () {
    expect(TranslationStatus::Untranslated->value)->toBe('untranslated')
        ->and(TranslationStatus::Translated->value)->toBe('translated')
        ->and(TranslationStatus::NeedsReview->value)->toBe('needs_review')
        ->and(TranslationStatus::Approved->value)->toBe('approved');
});
