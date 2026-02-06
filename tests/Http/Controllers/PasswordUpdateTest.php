<?php

namespace Outhebox\TranslationsUI\Tests\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Outhebox\TranslationsUI\Enums\RoleEnum;
use Outhebox\TranslationsUI\Models\Contributor;
use Outhebox\TranslationsUI\Tests\TestCase;

class PasswordUpdateTest extends TestCase
{
    /** @test */
    public function translator_can_update_password_when_default_guard_is_web()
    {
        // Set default guard to something else
        config(['auth.defaults.guard' => 'web']);

        $translator = Contributor::factory()->create([
            'role' => RoleEnum::translator,
            'password' => Hash::make('old-password'),
        ]);

        $response = $this->actingAs($translator, 'translations')
            ->from(route('ltu.profile.edit'))
            ->put(route('ltu.profile.password.update'), [
                'current_password' => 'old-password',
                'password' => 'new-password123',
                'password_confirmation' => 'new-password123',
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('ltu.profile.edit'));

        $this->assertTrue(Hash::check('new-password123', $translator->refresh()->password));
    }
}
