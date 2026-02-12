<?php

use Outhebox\Translations\Enums\ContributorStatus;

it('has three statuses', function () {
    expect(ContributorStatus::cases())->toHaveCount(3);
});

it('has correct labels', function () {
    expect(ContributorStatus::Invited->getLabel())->toBe('Invited')
        ->and(ContributorStatus::Active->getLabel())->toBe('Active')
        ->and(ContributorStatus::Inactive->getLabel())->toBe('Inactive');
});

it('has correct colors', function () {
    expect(ContributorStatus::Invited->getColor())->toBe('yellow')
        ->and(ContributorStatus::Active->getColor())->toBe('green')
        ->and(ContributorStatus::Inactive->getColor())->toBe('red');
});

it('has correct string values', function () {
    expect(ContributorStatus::Invited->value)->toBe('invited')
        ->and(ContributorStatus::Active->value)->toBe('active')
        ->and(ContributorStatus::Inactive->value)->toBe('inactive');
});
