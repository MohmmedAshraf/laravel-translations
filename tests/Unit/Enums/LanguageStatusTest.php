<?php

use Outhebox\Translations\Enums\LanguageStatus;

it('has four statuses', function () {
    expect(LanguageStatus::cases())->toHaveCount(4);
});

it('has correct labels', function () {
    expect(LanguageStatus::Completed->getLabel())->toBe('Completed')
        ->and(LanguageStatus::InProgress->getLabel())->toBe('In Progress')
        ->and(LanguageStatus::NeedsReview->getLabel())->toBe('Needs Review')
        ->and(LanguageStatus::NotStarted->getLabel())->toBe('Not Started');
});

it('has correct colors', function () {
    expect(LanguageStatus::Completed->getColor())->toBe('green')
        ->and(LanguageStatus::InProgress->getColor())->toBe('blue')
        ->and(LanguageStatus::NeedsReview->getColor())->toBe('amber')
        ->and(LanguageStatus::NotStarted->getColor())->toBe('neutral');
});

it('has correct icons', function () {
    expect(LanguageStatus::Completed->getIcon())->toBe('check-circle')
        ->and(LanguageStatus::InProgress->getIcon())->toBe('clock')
        ->and(LanguageStatus::NeedsReview->getIcon())->toBe('alert-circle')
        ->and(LanguageStatus::NotStarted->getIcon())->toBe('x-circle');
});

it('has correct string values', function () {
    expect(LanguageStatus::Completed->value)->toBe('completed')
        ->and(LanguageStatus::InProgress->value)->toBe('in_progress')
        ->and(LanguageStatus::NeedsReview->value)->toBe('needs_review')
        ->and(LanguageStatus::NotStarted->value)->toBe('not_started');
});
