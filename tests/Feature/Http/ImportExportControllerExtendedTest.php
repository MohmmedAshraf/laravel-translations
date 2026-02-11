<?php

use Illuminate\Support\Facades\Queue;
use Outhebox\Translations\Jobs\ExportTranslationsJob;
use Outhebox\Translations\Jobs\ImportTranslationsJob;
use Outhebox\Translations\Models\Contributor;
use Outhebox\Translations\Models\Group;
use Outhebox\Translations\Models\Language;
use Outhebox\Translations\Models\Translation;
use Outhebox\Translations\Models\TranslationKey;

beforeEach(function () {
    useContributorAuth();
    config(['translations.lang_path' => __DIR__.'/../../Fixtures/lang']);
    $this->contributor = Contributor::factory()->owner()->create();
});

it('queues import job when queue is configured', function () {
    Queue::fake();
    config(['translations.queue.connection' => 'sync']);

    $this->actingAs($this->contributor, 'translations')
        ->post(route('ltu.import'), ['fresh' => true, 'overwrite' => false])
        ->assertRedirect()
        ->assertSessionHas('success', 'Import queued. Check status for progress.');

    Queue::assertPushed(ImportTranslationsJob::class, function ($job) {
        return $job->fresh === true && $job->overwrite === false;
    });
});

it('queues export job when queue is configured', function () {
    Queue::fake();
    config(['translations.queue.connection' => 'sync']);

    $this->actingAs($this->contributor, 'translations')
        ->post(route('ltu.export'), ['locale' => 'en', 'group' => 'auth'])
        ->assertRedirect()
        ->assertSessionHas('success', 'Export queued. Check status for progress.');

    Queue::assertPushed(ExportTranslationsJob::class);
});

it('handles import failure gracefully', function () {
    config(['translations.lang_path' => '/nonexistent/path/that/will/fail']);

    $this->actingAs($this->contributor, 'translations')
        ->post(route('ltu.import'))
        ->assertRedirect();
});

it('handles export failure gracefully', function () {
    config(['translations.lang_path' => '/nonexistent/path/that/will/fail']);

    $en = Language::factory()->create(['code' => 'en']);
    $group = Group::factory()->create(['name' => 'test']);
    $key = TranslationKey::factory()->create(['group_id' => $group->id, 'key' => 'hello']);
    Translation::factory()->create([
        'translation_key_id' => $key->id,
        'language_id' => $en->id,
        'value' => 'Hello',
    ]);

    $this->actingAs($this->contributor, 'translations')
        ->post(route('ltu.export'))
        ->assertRedirect();
});

it('does not queue when queue connection is null', function () {
    Queue::fake();
    config(['translations.queue.connection' => null]);

    $this->actingAs($this->contributor, 'translations')
        ->post(route('ltu.import'))
        ->assertRedirect();

    Queue::assertNotPushed(ImportTranslationsJob::class);
});

it('does not queue when queue connection config does not exist', function () {
    Queue::fake();
    config(['translations.queue.connection' => 'nonexistent_connection']);

    $this->actingAs($this->contributor, 'translations')
        ->post(route('ltu.import'))
        ->assertRedirect();

    Queue::assertNotPushed(ImportTranslationsJob::class);
});
