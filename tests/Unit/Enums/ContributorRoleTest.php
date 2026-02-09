<?php

use Outhebox\Translations\Enums\ContributorRole;

it('has five roles', function () {
    expect(ContributorRole::cases())->toHaveCount(5);
    expect(ContributorRole::Owner)->not->toBeNull();
    expect(ContributorRole::Admin)->not->toBeNull();
    expect(ContributorRole::Translator)->not->toBeNull();
    expect(ContributorRole::Reviewer)->not->toBeNull();
    expect(ContributorRole::Viewer)->not->toBeNull();
});

it('assigns correct hierarchy levels', function () {
    expect(ContributorRole::Owner->level())->toBe(100);
    expect(ContributorRole::Admin->level())->toBe(80);
    expect(ContributorRole::Reviewer->level())->toBe(60);
    expect(ContributorRole::Translator->level())->toBe(40);
    expect(ContributorRole::Viewer->level())->toBe(20);
});

it('checks is at least correctly', function () {
    expect(ContributorRole::Owner->isAtLeast(ContributorRole::Admin))->toBeTrue();
    expect(ContributorRole::Admin->isAtLeast(ContributorRole::Admin))->toBeTrue();
    expect(ContributorRole::Translator->isAtLeast(ContributorRole::Admin))->toBeFalse();
    expect(ContributorRole::Viewer->isAtLeast(ContributorRole::Translator))->toBeFalse();
    expect(ContributorRole::Reviewer->isAtLeast(ContributorRole::Translator))->toBeTrue();
});

it('owner can do everything', function () {
    $owner = ContributorRole::Owner;

    expect($owner->canEditTranslations())->toBeTrue();
    expect($owner->canApproveTranslations())->toBeTrue();
    expect($owner->canManageKeys())->toBeTrue();
    expect($owner->canImportExport())->toBeTrue();
    expect($owner->canManageContributors())->toBeTrue();
    expect($owner->canManageLanguages())->toBeTrue();
    expect($owner->canManageSettings())->toBeTrue();
});

it('admin cannot manage settings', function () {
    $admin = ContributorRole::Admin;

    expect($admin->canEditTranslations())->toBeTrue();
    expect($admin->canApproveTranslations())->toBeTrue();
    expect($admin->canManageKeys())->toBeTrue();
    expect($admin->canImportExport())->toBeTrue();
    expect($admin->canManageContributors())->toBeTrue();
    expect($admin->canManageLanguages())->toBeTrue();
    expect($admin->canManageSettings())->toBeFalse();
});

it('translator can only edit translations', function () {
    $translator = ContributorRole::Translator;

    expect($translator->canEditTranslations())->toBeTrue();
    expect($translator->canApproveTranslations())->toBeFalse();
    expect($translator->canManageKeys())->toBeFalse();
    expect($translator->canImportExport())->toBeFalse();
    expect($translator->canManageContributors())->toBeFalse();
    expect($translator->canManageLanguages())->toBeFalse();
    expect($translator->canManageSettings())->toBeFalse();
});

it('reviewer can approve but not edit', function () {
    $reviewer = ContributorRole::Reviewer;

    expect($reviewer->canApproveTranslations())->toBeTrue();
    expect($reviewer->canEditTranslations())->toBeFalse();
    expect($reviewer->canManageKeys())->toBeFalse();
    expect($reviewer->canImportExport())->toBeFalse();
    expect($reviewer->canManageContributors())->toBeFalse();
    expect($reviewer->canManageLanguages())->toBeFalse();
    expect($reviewer->canManageSettings())->toBeFalse();
});

it('viewer cannot do anything except view', function () {
    $viewer = ContributorRole::Viewer;

    expect($viewer->canEditTranslations())->toBeFalse();
    expect($viewer->canApproveTranslations())->toBeFalse();
    expect($viewer->canManageKeys())->toBeFalse();
    expect($viewer->canImportExport())->toBeFalse();
    expect($viewer->canManageContributors())->toBeFalse();
    expect($viewer->canManageLanguages())->toBeFalse();
    expect($viewer->canManageSettings())->toBeFalse();
});

it('has correct labels for all roles', function () {
    expect(ContributorRole::Owner->getLabel())->toBe('Owner');
    expect(ContributorRole::Admin->getLabel())->toBe('Admin');
    expect(ContributorRole::Translator->getLabel())->toBe('Translator');
    expect(ContributorRole::Reviewer->getLabel())->toBe('Reviewer');
    expect(ContributorRole::Viewer->getLabel())->toBe('Viewer');
});
