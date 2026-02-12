<?php

use Outhebox\Translations\Models\Group;
use Outhebox\Translations\Models\TranslationKey;

it('returns display name without namespace', function () {
    $group = Group::factory()->create(['name' => 'auth', 'namespace' => null]);

    expect($group->displayName())->toBe('auth');
});

it('returns display name with namespace', function () {
    $group = Group::factory()->create(['name' => 'messages', 'namespace' => 'vendor-pkg']);

    expect($group->displayName())->toBe('vendor-pkg::messages');
});

it('detects json format', function () {
    $group = Group::factory()->create(['file_format' => 'json']);

    expect($group->isJson())->toBeTrue();
});

it('detects non-json format', function () {
    $group = Group::factory()->create(['file_format' => 'php']);

    expect($group->isJson())->toBeFalse();
});

it('has translation keys relationship', function () {
    $group = Group::factory()->create();
    TranslationKey::factory()->count(3)->create(['group_id' => $group->id]);

    expect($group->translationKeys()->count())->toBe(3);
});
