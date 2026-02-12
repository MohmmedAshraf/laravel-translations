<?php

use Illuminate\Support\Facades\Queue;
use Outhebox\Translations\Jobs\ReplicateKeysJob;
use Outhebox\Translations\Models\Language;
use Outhebox\Translations\Models\TranslationKey;

it('dispatches to the correct queue', function () {
    Queue::fake();
    config(['translations.queue.name' => 'custom-queue']);

    $language = Language::factory()->create();

    ReplicateKeysJob::dispatch($language);

    Queue::assertPushedOn('custom-queue', ReplicateKeysJob::class);
});

it('replicates keys for the given language', function () {
    $language = Language::factory()->create(['active' => true]);
    TranslationKey::factory()->count(3)->create();

    $job = new ReplicateKeysJob($language);
    $job->handle(app(Outhebox\Translations\Services\KeyReplicator::class));

    expect($language->translations()->count())->toBe(3);
});

it('serializes the language model', function () {
    $language = Language::factory()->create();

    $job = new ReplicateKeysJob($language);

    expect($job->language->id)->toBe($language->id);
});
