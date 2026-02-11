<?php

use Outhebox\Translations\Http\Middleware\TranslationsRole;
use Outhebox\Translations\Models\Contributor;
use Outhebox\Translations\Models\Language;

beforeEach(function () {
    useContributorAuth();
    Language::factory()->source()->create();
});

it('blocks unauthenticated user via TranslationsRole middleware', function () {
    $middleware = new TranslationsRole;
    $request = Illuminate\Http\Request::create('/test');

    $response = null;
    try {
        $middleware->handle($request, fn () => response('ok'), 'admin');
    } catch (Symfony\Component\HttpKernel\Exception\HttpException $e) {
        $response = $e;
    }

    expect($response)->not->toBeNull()
        ->and($response->getStatusCode())->toBe(403);
});

it('blocks insufficient role via TranslationsRole middleware', function () {
    $viewer = Contributor::factory()->viewer()->create();

    $this->actingAs($viewer, 'translations')
        ->post(route('ltu.languages.store'), ['language_ids' => []])
        ->assertForbidden();
});

it('allows sufficient role via TranslationsRole middleware', function () {
    $admin = Contributor::factory()->admin()->create();

    $this->actingAs($admin, 'translations')
        ->post(route('ltu.languages.store'), ['language_ids' => []])
        ->assertRedirect();
});
