<?php

use Outhebox\Translations\Services\Scanner\FileWalker;

beforeEach(function () {
    $this->walker = new FileWalker;
    $this->tempDir = sys_get_temp_dir().'/file_walker_test_'.uniqid();
    mkdir($this->tempDir, 0755, true);
    mkdir($this->tempDir.'/sub', 0755, true);
    file_put_contents($this->tempDir.'/test.php', '<?php echo 1;');
    file_put_contents($this->tempDir.'/test.js', 'console.log(1)');
    file_put_contents($this->tempDir.'/sub/nested.php', '<?php echo 2;');
});

afterEach(function () {
    @unlink($this->tempDir.'/test.php');
    @unlink($this->tempDir.'/test.js');
    @unlink($this->tempDir.'/sub/nested.php');
    @rmdir($this->tempDir.'/sub');
    @rmdir($this->tempDir);
});

it('walks files in directories', function () {
    $files = iterator_to_array($this->walker->walk([$this->tempDir], [], []));

    expect($files)->toHaveCount(3);
});

it('filters by extension', function () {
    $files = iterator_to_array($this->walker->walk([$this->tempDir], [], ['php']));

    expect($files)->toHaveCount(2)
        ->and(collect($files)->every(fn ($f) => str_ends_with($f['absolutePath'], '.php')))->toBeTrue();
});

it('ignores specified paths', function () {
    $files = iterator_to_array($this->walker->walk([$this->tempDir], ['sub'], []));

    expect($files)->toHaveCount(2);
});

it('skips non-directory paths', function () {
    $files = iterator_to_array($this->walker->walk(['/nonexistent/path'], [], []));

    expect($files)->toHaveCount(0);
});

it('includes absolutePath and relativePath in results', function () {
    $files = iterator_to_array($this->walker->walk([$this->tempDir], [], ['php']));

    expect($files[0])->toHaveKeys(['absolutePath', 'relativePath'])
        ->and($files[0]['absolutePath'])->toBeString()
        ->and($files[0]['relativePath'])->toBeString();
});
