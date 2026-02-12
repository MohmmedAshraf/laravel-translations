<?php

use Inertia\Testing\AssertableInertia as Assert;
use Outhebox\Translations\Models\Contributor;
use Outhebox\Translations\Models\Group;
use Outhebox\Translations\Models\Language;
use Outhebox\Translations\Models\Translation;
use Outhebox\Translations\Models\TranslationKey;

beforeEach(function () {
    $this->contributor = Contributor::factory()->owner()->create();
});

it('loads the languages page', function () {
    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.languages.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('translations/languages')
            ->has('data')
            ->has('tableConfig')
            ->has('availableLanguages')
            ->has('totalKeys')
        );
});

it('loads the phrases index page', function () {
    Language::factory()->source()->create();
    $language = Language::factory()->create();

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.phrases.index', $language))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('translations/phrases/index')
            ->has('data')
            ->has('tableConfig')
            ->has('language')
        );
});

it('loads the phrase edit page', function () {
    Language::factory()->source()->create();
    $language = Language::factory()->create();
    $group = Group::factory()->create();
    $key = TranslationKey::factory()->for($group)->create();
    Translation::factory()->for($key)->for($language)->create();

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.phrases.edit', [$language, $key]))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('translations/phrases/edit')
            ->has('language')
            ->has('translationKey')
            ->has('translationIsEmpty')
        );
});

it('loads the source language index page', function () {
    Language::factory()->source()->create();

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.source.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('translations/source-language/index')
        );
});

it('loads the groups page', function () {
    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.groups.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('translations/groups/index')
        );
});

it('loads the login page', function () {
    $this->get(route('ltu.login'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('translations/auth/login')
        );
});

it('redirects unauthenticated users to login', function () {
    $this->get(route('ltu.languages.index'))
        ->assertRedirect(route('ltu.login'));
});

it('publishes assets to the correct location', function () {
    $publishPath = public_path('vendor/translations');

    $this->artisan('vendor:publish', [
        '--tag' => 'translations-assets',
        '--force' => true,
    ])->assertSuccessful();

    expect(file_exists($publishPath.'/js/app.js'))->toBeTrue()
        ->and(file_exists($publishPath.'/css/app.css'))->toBeTrue();
});

it('loads the blade template view', function () {
    $view = view('translations::app');

    expect($view->getName())->toBe('translations::app');
});
