<?php

use Outhebox\Translations\Enums\ContributorRole;

it('has five roles', function () {
    expect(ContributorRole::cases())->toHaveCount(5)
        ->and(ContributorRole::Owner)->not->toBeNull()
        ->and(ContributorRole::Admin)->not->toBeNull()
        ->and(ContributorRole::Translator)->not->toBeNull()
        ->and(ContributorRole::Reviewer)->not->toBeNull()
        ->and(ContributorRole::Viewer)->not->toBeNull();
});

it('assigns correct hierarchy levels', function () {
    expect(ContributorRole::Owner->level())->toBe(100)
        ->and(ContributorRole::Admin->level())->toBe(80)
        ->and(ContributorRole::Reviewer->level())->toBe(60)
        ->and(ContributorRole::Translator->level())->toBe(40)
        ->and(ContributorRole::Viewer->level())->toBe(20);
});

it('checks is at least correctly', function () {
    expect(ContributorRole::Owner->isAtLeast(ContributorRole::Admin))->toBeTrue()
        ->and(ContributorRole::Admin->isAtLeast(ContributorRole::Admin))->toBeTrue()
        ->and(ContributorRole::Translator->isAtLeast(ContributorRole::Admin))->toBeFalse()
        ->and(ContributorRole::Viewer->isAtLeast(ContributorRole::Translator))->toBeFalse()
        ->and(ContributorRole::Reviewer->isAtLeast(ContributorRole::Translator))->toBeTrue();
});

it('owner can do everything', function () {
    $owner = ContributorRole::Owner;

    expect($owner->canEditTranslations())->toBeTrue()
        ->and($owner->canApproveTranslations())->toBeTrue()
        ->and($owner->canManageKeys())->toBeTrue()
        ->and($owner->canImportExport())->toBeTrue()
        ->and($owner->canManageContributors())->toBeTrue()
        ->and($owner->canManageLanguages())->toBeTrue()
        ->and($owner->canManageSettings())->toBeTrue();
});

it('admin cannot manage settings', function () {
    $admin = ContributorRole::Admin;

    expect($admin->canEditTranslations())->toBeTrue()
        ->and($admin->canApproveTranslations())->toBeTrue()
        ->and($admin->canManageKeys())->toBeTrue()
        ->and($admin->canImportExport())->toBeTrue()
        ->and($admin->canManageContributors())->toBeTrue()
        ->and($admin->canManageLanguages())->toBeTrue()
        ->and($admin->canManageSettings())->toBeFalse();
});

it('translator can only edit translations', function () {
    $translator = ContributorRole::Translator;

    expect($translator->canEditTranslations())->toBeTrue()
        ->and($translator->canApproveTranslations())->toBeFalse()
        ->and($translator->canManageKeys())->toBeFalse()
        ->and($translator->canImportExport())->toBeFalse()
        ->and($translator->canManageContributors())->toBeFalse()
        ->and($translator->canManageLanguages())->toBeFalse()
        ->and($translator->canManageSettings())->toBeFalse();
});

it('reviewer can approve but not edit', function () {
    $reviewer = ContributorRole::Reviewer;

    expect($reviewer->canApproveTranslations())->toBeTrue()
        ->and($reviewer->canEditTranslations())->toBeFalse()
        ->and($reviewer->canManageKeys())->toBeFalse()
        ->and($reviewer->canImportExport())->toBeFalse()
        ->and($reviewer->canManageContributors())->toBeFalse()
        ->and($reviewer->canManageLanguages())->toBeFalse()
        ->and($reviewer->canManageSettings())->toBeFalse();
});

it('viewer cannot do anything except view', function () {
    $viewer = ContributorRole::Viewer;

    expect($viewer->canEditTranslations())->toBeFalse()
        ->and($viewer->canApproveTranslations())->toBeFalse()
        ->and($viewer->canManageKeys())->toBeFalse()
        ->and($viewer->canImportExport())->toBeFalse()
        ->and($viewer->canManageContributors())->toBeFalse()
        ->and($viewer->canManageLanguages())->toBeFalse()
        ->and($viewer->canManageSettings())->toBeFalse();
});

it('has correct labels for all roles', function () {
    expect(ContributorRole::Owner->getLabel())->toBe('Owner')
        ->and(ContributorRole::Admin->getLabel())->toBe('Admin')
        ->and(ContributorRole::Translator->getLabel())->toBe('Translator')
        ->and(ContributorRole::Reviewer->getLabel())->toBe('Reviewer')
        ->and(ContributorRole::Viewer->getLabel())->toBe('Viewer');
});

it('has correct colors for all roles', function () {
    expect(ContributorRole::Owner->getColor())->toBe('purple')
        ->and(ContributorRole::Admin->getColor())->toBe('blue')
        ->and(ContributorRole::Translator->getColor())->toBe('yellow')
        ->and(ContributorRole::Reviewer->getColor())->toBe('green')
        ->and(ContributorRole::Viewer->getColor())->toBe('gray');
});

it('maps canTranslate to canEditTranslations', function () {
    expect(ContributorRole::Owner->canTranslate())->toBeTrue()
        ->and(ContributorRole::Translator->canTranslate())->toBeTrue()
        ->and(ContributorRole::Reviewer->canTranslate())->toBeFalse()
        ->and(ContributorRole::Viewer->canTranslate())->toBeFalse();
});

it('maps canReview to canApproveTranslations', function () {
    expect(ContributorRole::Owner->canReview())->toBeTrue()
        ->and(ContributorRole::Reviewer->canReview())->toBeTrue()
        ->and(ContributorRole::Translator->canReview())->toBeFalse()
        ->and(ContributorRole::Viewer->canReview())->toBeFalse();
});
