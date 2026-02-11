<?php

use Illuminate\Support\Facades\Auth;
use Outhebox\Translations\Models\Contributor;
use Outhebox\Translations\Policies\TranslationKeyPolicy;

beforeEach(function () {
    useContributorAuth();
    $this->policy = app(TranslationKeyPolicy::class);
});

it('allows owner to create keys', function () {
    Auth::guard('translations')->login(Contributor::factory()->owner()->create());
    expect($this->policy->create())->toBeTrue();
});

it('allows admin to create keys', function () {
    Auth::guard('translations')->login(Contributor::factory()->admin()->create());
    expect($this->policy->create())->toBeTrue();
});

it('denies translator from creating keys', function () {
    Auth::guard('translations')->login(Contributor::factory()->translator()->create());
    expect($this->policy->create())->toBeFalse();
});

it('denies reviewer from creating keys', function () {
    Auth::guard('translations')->login(Contributor::factory()->reviewer()->create());
    expect($this->policy->create())->toBeFalse();
});

it('denies viewer from creating keys', function () {
    Auth::guard('translations')->login(Contributor::factory()->viewer()->create());
    expect($this->policy->create())->toBeFalse();
});

it('allows owner to delete keys', function () {
    Auth::guard('translations')->login(Contributor::factory()->owner()->create());
    expect($this->policy->delete())->toBeTrue();
});

it('denies translator from deleting keys', function () {
    Auth::guard('translations')->login(Contributor::factory()->translator()->create());
    expect($this->policy->delete())->toBeFalse();
});

it('allows admin to update keys', function () {
    Auth::guard('translations')->login(Contributor::factory()->admin()->create());
    expect($this->policy->update())->toBeTrue();
});

it('denies translator from updating keys', function () {
    Auth::guard('translations')->login(Contributor::factory()->translator()->create());
    expect($this->policy->update())->toBeFalse();
});

it('denies unauthenticated user from all actions', function () {
    expect($this->policy->create())->toBeFalse()
        ->and($this->policy->update())->toBeFalse()
        ->and($this->policy->delete())->toBeFalse();
});
