<?php

use Illuminate\Support\Facades\Auth;
use Outhebox\Translations\Models\Contributor;
use Outhebox\Translations\Models\Group;
use Outhebox\Translations\Models\Language;
use Outhebox\Translations\Models\Translation;
use Outhebox\Translations\Models\TranslationKey;
use Outhebox\Translations\Policies\TranslationPolicy;

beforeEach(function () {
    useContributorAuth();

    $this->source = Language::factory()->source()->create();
    $this->arabic = Language::factory()->create(['code' => 'ar', 'name' => 'Arabic']);
    $this->french = Language::factory()->create(['code' => 'fr', 'name' => 'French']);

    $group = Group::factory()->create();
    $key = TranslationKey::factory()->for($group)->create();

    $this->arabicTranslation = Translation::factory()->create([
        'translation_key_id' => $key->id,
        'language_id' => $this->arabic->id,
    ]);

    $this->frenchTranslation = Translation::factory()->create([
        'translation_key_id' => $key->id,
        'language_id' => $this->french->id,
    ]);

    $this->policy = app(TranslationPolicy::class);
});

it('allows translator to update assigned language', function () {
    $translator = Contributor::factory()->translator()->create();
    $translator->languages()->attach([$this->arabic->id]);
    Auth::guard('translations')->login($translator);

    expect($this->policy->update($this->arabicTranslation))->toBeTrue();
});

it('denies translator from updating unassigned language', function () {
    $translator = Contributor::factory()->translator()->create();
    $translator->languages()->attach([$this->arabic->id]);
    Auth::guard('translations')->login($translator);

    expect($this->policy->update($this->frenchTranslation))->toBeFalse();
});

it('denies translator with no assignments from updating', function () {
    $translator = Contributor::factory()->translator()->create();
    Auth::guard('translations')->login($translator);

    expect($this->policy->update($this->arabicTranslation))->toBeFalse()
        ->and($this->policy->update($this->frenchTranslation))->toBeFalse();
});

it('allows admin to update any language', function () {
    $admin = Contributor::factory()->admin()->create();
    Auth::guard('translations')->login($admin);

    expect($this->policy->update($this->arabicTranslation))->toBeTrue()
        ->and($this->policy->update($this->frenchTranslation))->toBeTrue();
});

it('allows reviewer to approve', function () {
    $reviewer = Contributor::factory()->reviewer()->create();
    Auth::guard('translations')->login($reviewer);

    expect($this->policy->approve($this->arabicTranslation))->toBeTrue();
});

it('denies translator from approving', function () {
    $translator = Contributor::factory()->translator()->create();
    Auth::guard('translations')->login($translator);

    expect($this->policy->approve($this->arabicTranslation))->toBeFalse();
});

it('allows reviewer to reject', function () {
    $reviewer = Contributor::factory()->reviewer()->create();
    Auth::guard('translations')->login($reviewer);

    expect($this->policy->reject($this->arabicTranslation))->toBeTrue();
});

it('denies viewer from updating', function () {
    $viewer = Contributor::factory()->viewer()->create();
    Auth::guard('translations')->login($viewer);

    expect($this->policy->update($this->arabicTranslation))->toBeFalse();
});
