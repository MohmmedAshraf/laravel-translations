<?php

namespace Outhebox\LaravelTranslations\Tests;

use Illuminate\Filesystem\Filesystem;
use SplFileInfo;

class FilesystemMock extends Filesystem
{
    public function directories($directory): array
    {
        return ['en', 'vendor'];
    }

    public function allFiles($directory, $hidden = false): array
    {
        return [new SplFileInfo('auth.php'), new SplFileInfo('validation.json')];
    }
}
