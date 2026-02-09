<?php

use Outhebox\Translations\Services\Scanner\FileWalker;

beforeEach(function () {
    $this->tempDir = sys_get_temp_dir().'/test_walker_'.uniqid();
    mkdir($this->tempDir, 0755, true);
});

afterEach(function () {
    $files = glob($this->tempDir.'/*') ?: [];
    foreach ($files as $file) {
        @unlink($file);
    }

    $subdirs = glob($this->tempDir.'/*', GLOB_ONLYDIR) ?: [];
    foreach ($subdirs as $subdir) {
        $subFiles = glob($subdir.'/*') ?: [];
        foreach ($subFiles as $f) {
            @unlink($f);
        }
        @rmdir($subdir);
    }

    @rmdir($this->tempDir);
});

it('walks directories and finds files matching extensions', function () {
    file_put_contents($this->tempDir.'/test.blade.php', '<h1>Hello</h1>');
    file_put_contents($this->tempDir.'/style.css', 'body {}');

    $walker = new FileWalker;
    $results = iterator_to_array($walker->walk([$this->tempDir], [], ['blade.php']));

    expect($results)->toHaveCount(1)
        ->and($results[0]['absolutePath'])->toEndWith('test.blade.php');
});

it('ignores files in ignored paths', function () {
    $subDir = $this->tempDir.'/vendor';
    mkdir($subDir, 0755, true);
    file_put_contents($subDir.'/ignored.blade.php', '<p>Ignored</p>');
    file_put_contents($this->tempDir.'/included.blade.php', '<p>Included</p>');

    $walker = new FileWalker;
    $results = iterator_to_array($walker->walk([$this->tempDir], ['vendor'], ['blade.php']));

    expect($results)->toHaveCount(1)
        ->and($results[0]['absolutePath'])->toEndWith('included.blade.php');
});

it('returns relative paths when under base_path', function () {
    $dir = base_path('storage/app/test_walker_'.uniqid());
    mkdir($dir, 0755, true);
    file_put_contents($dir.'/test.blade.php', '<h1>Hello</h1>');

    $walker = new FileWalker;
    $results = iterator_to_array($walker->walk([$dir], [], ['blade.php']));

    expect($results[0]['relativePath'])->toStartWith('storage/app/');

    @unlink($dir.'/test.blade.php');
    @rmdir($dir);
});

it('handles non-existent directories gracefully', function () {
    $walker = new FileWalker;
    $results = iterator_to_array($walker->walk(['/nonexistent/path/'.uniqid()], [], ['php']));

    expect($results)->toBeEmpty();
});

it('finds files across multiple extensions', function () {
    file_put_contents($this->tempDir.'/view.blade.php', '<h1>Hello</h1>');
    file_put_contents($this->tempDir.'/component.vue', '<template></template>');
    file_put_contents($this->tempDir.'/readme.md', '# Docs');

    $walker = new FileWalker;
    $results = iterator_to_array($walker->walk([$this->tempDir], [], ['blade.php', 'vue']));

    expect($results)->toHaveCount(2);
});

it('yields nothing when no files match', function () {
    file_put_contents($this->tempDir.'/notes.txt', 'some text');

    $walker = new FileWalker;
    $results = iterator_to_array($walker->walk([$this->tempDir], [], ['blade.php']));

    expect($results)->toBeEmpty();
});
