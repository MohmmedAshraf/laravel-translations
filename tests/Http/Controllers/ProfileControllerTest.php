<?php

use Illuminate\Support\Facades\Hash;
use Outhebox\TranslationsUI\Models\Language;

use function Pest\Faker\fake;

it('can access the profile page', function () {
    $this->actingAs($this->translator, 'translations')
        ->get(route('ltu.profile.edit'))
        ->assertOk();
});

it('can update profile information', function () {
    $name = fake()->name;
    $email = fake()->safeEmail;

    $this->actingAs($this->translator, 'translations')
        ->from(route('ltu.profile.edit'))
        ->patch(route('ltu.profile.update'), [
            'name' => $name,
            'email' => $email,
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('ltu.profile.edit'));

    $this->translator->refresh();

    expect($this->translator->name)->toBe($name)
        ->and($this->translator->email)->toBe($email);
});

it('can update the password', function () {
    $this->actingAs($this->translator, 'translations')
        ->from(route('ltu.profile.edit'))
        ->put(route('ltu.profile.password.update'), [
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('ltu.profile.edit'));

    $this->translator->refresh();

    expect(Hash::check('new-password', $this->translator->refresh()->password))->toBeTrue();
});

it('can update the language', function () {
    $language = Language::factory(['code' => 'id'])->create();
    $this->actingAs($this->translator, 'translations')
        ->from(route('ltu.profile.edit'))
        ->patch(route('ltu.profile.language.update'), [
            'language' => $language,
        ])
        ->assertStatus(302)
        ->assertSessionHasNoErrors('language')
        ->assertRedirect(route('ltu.profile.edit'));

    $this->translator->refresh();

    expect($this->translator->lang)->toBe($language['code']);
});

it('can update profile information with password', function () {
    $response = $this
        ->actingAs($this->translator, 'translations')
        ->from(route('ltu.profile.edit'))
        ->put(route('ltu.profile.password.update'), [
            'current_password' => 'wrong-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

    $response
        ->assertSessionHasErrors('current_password')
        ->assertRedirect(route('ltu.profile.edit'));
});
