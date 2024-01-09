<?php

namespace Outhebox\LaravelTranslations\Data\Shared;

use Outhebox\LaravelTranslations\Enums\NotificationType;
use Spatie\LaravelData\Data;

class NotificationData extends Data
{
    public function __construct(
        public NotificationType $type,
        public string $body
    ) {
    }
}
