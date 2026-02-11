<?php

use Illuminate\Support\Facades\Hash;
use Outhebox\Translations\Models\Contributor;
use Outhebox\Translations\Models\Language;

beforeEach(function () {
    useContributorAuth();
    Language::factory()->source()->create();
});

it('updates contributor profile', function () {
    $contributor = Contributor::factory()->translator()->create([
        'name' => 'Old Name',
        'email' => 'old@example.com',
    ]);

    $this->actingAs($contributor, 'translations')
        ->put(route('ltu.account.update'), [
            'name' => 'New Name',
            'email' => 'new@example.com',
        ])
        ->assertRedirect();

    $contributor->refresh();
    expect($contributor->name)->toBe('New Name');
    expect($contributor->email)->toBe('new@example.com');
});

it('validates required profile fields', function () {
    $contributor = Contributor::factory()->translator()->create();

    $this->actingAs($contributor, 'translations')
        ->put(route('ltu.account.update'), [
            'name' => '',
            'email' => '',
        ])
        ->assertSessionHasErrors(['name', 'email']);
});

it('validates unique email on profile update', function () {
    $contributor = Contributor::factory()->translator()->create();
    Contributor::factory()->create(['email' => 'taken@example.com']);

    $this->actingAs($contributor, 'translations')
        ->put(route('ltu.account.update'), [
            'name' => 'Test',
            'email' => 'taken@example.com',
        ])
        ->assertSessionHasErrors('email');
});

it('allows keeping own email on profile update', function () {
    $contributor = Contributor::factory()->translator()->create([
        'email' => 'my@example.com',
    ]);

    $this->actingAs($contributor, 'translations')
        ->put(route('ltu.account.update'), [
            'name' => 'Updated Name',
            'email' => 'my@example.com',
        ])
        ->assertRedirect()
        ->assertSessionHasNoErrors();
});

it('updates contributor password', function () {
    $contributor = Contributor::factory()->translator()->create([
        'password' => bcrypt('old-password'),
    ]);

    $this->actingAs($contributor, 'translations')
        ->put(route('ltu.account.password'), [
            'current_password' => 'old-password',
            'password' => 'new-password123',
            'password_confirmation' => 'new-password123',
        ])
        ->assertRedirect()
        ->assertSessionHas('success');

    $contributor->refresh();
    expect(Hash::check('new-password123', $contributor->password))->toBeTrue();
});

it('rejects wrong current password', function () {
    $contributor = Contributor::factory()->translator()->create([
        'password' => bcrypt('correct-password'),
    ]);

    $this->actingAs($contributor, 'translations')
        ->put(route('ltu.account.password'), [
            'current_password' => 'wrong-password',
            'password' => 'new-password123',
            'password_confirmation' => 'new-password123',
        ])
        ->assertSessionHasErrors('current_password');
});

it('requires password confirmation', function () {
    $contributor = Contributor::factory()->translator()->create([
        'password' => bcrypt('old-password'),
    ]);

    $this->actingAs($contributor, 'translations')
        ->put(route('ltu.account.password'), [
            'current_password' => 'old-password',
            'password' => 'new-password123',
            'password_confirmation' => 'different-password',
        ])
        ->assertSessionHasErrors('password');
});
