<?php

namespace Outhebox\TranslationsUI\Tests\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use JsonException;
use Outhebox\TranslationsUI\Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    /** @test */
    public function test_profile_page_is_displayed(): void
    {
        $this->actingAs($this->translator, 'translations')
            ->get(route('ltu.profile.edit'))
            ->assertOk();
    }

    /**
     * @test
     *
     * @throws JsonException
     */
    public function test_profile_information_can_be_updated(): void
    {
        $this->actingAs($this->translator, 'translations')
            ->from(route('ltu.profile.edit'))
            ->patch(route('ltu.profile.update'), [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('ltu.profile.edit'));

        $this->translator->refresh();

        $this->assertSame('Test User', $this->translator->name);
        $this->assertSame('test@example.com', $this->translator->email);
    }

    /**
     * @test
     *
     * @throws JsonException
     */
    public function test_password_can_be_updated(): void
    {
        $this->withoutExceptionHandling();

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

        $this->assertTrue(Hash::check('new-password', $this->translator->refresh()->password));
    }

    /** @test */
    public function test_correct_password_must_be_provided_to_update_password(): void
    {
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
    }
}
