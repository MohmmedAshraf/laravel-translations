<?php

namespace Outhebox\LaravelTranslations\Enums;

enum NotificationType: string
{
    case Success = 'success';
    case Error = 'error';
    case Warning = 'warning';
    case Info = 'info';
    case Default = 'default';
}
