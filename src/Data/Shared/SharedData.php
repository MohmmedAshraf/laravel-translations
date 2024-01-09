<?php

namespace Outhebox\LaravelTranslations\Data\Shared;

use Closure;
use Spatie\LaravelData\Data;

class SharedData extends Data
{
    public function __construct(
        public ?Closure $user = null,
        public ?NotificationData $notification = null
    ) {
        $this->shareNotification();
    }

    protected function shareNotification(): void
    {
        if (session('notification')) {
            $this->notification = new NotificationData(
                ...session('notification')
            );
        }
    }
}
