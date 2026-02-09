<?php

namespace Outhebox\Translations\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Outhebox\Translations\Models\Language;
use Outhebox\Translations\Services\KeyReplicator;

class ReplicateKeysJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Language $language,
    ) {
        $this->onConnection(config('translations.queue.connection'));
        $this->onQueue(config('translations.queue.name', 'translations'));
    }

    public function handle(KeyReplicator $replicator): void
    {
        $replicator->replicateForLanguage($this->language);
    }
}
