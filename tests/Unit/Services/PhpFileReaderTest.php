<?php

use Outhebox\Translations\Services\Importer\PhpFileReader;

beforeEach(function () {
    $this->reader = new PhpFileReader;
    $this->fixturePath = __DIR__.'/../../Fixtures/lang';
});

it('reads a php translation file', function () {
    $result = $this->reader->read($this->fixturePath.'/en/auth.php', $this->fixturePath);

    expect($result)->toBeArray()
        ->and($result)->not->toBeEmpty();
});

it('returns empty array for nonexistent file', function () {
    $result = $this->reader->read('/nonexistent/file.php');

    expect($result)->toBe([]);
});

it('returns empty array for non-php file', function () {
    $tempFile = tempnam(sys_get_temp_dir(), 'test');
    rename($tempFile, $tempFile.'.txt');
    file_put_contents($tempFile.'.txt', 'test');

    $result = $this->reader->read($tempFile.'.txt');

    @unlink($tempFile.'.txt');

    expect($result)->toBe([]);
});

it('discovers locales from lang path', function () {
    $locales = $this->reader->discoverLocales($this->fixturePath);

    expect($locales)->toBeArray()
        ->and($locales)->toContain('en');
});

it('returns empty array for nonexistent lang path', function () {
    $locales = $this->reader->discoverLocales('/nonexistent/path');

    expect($locales)->toBe([]);
});

it('discovers php files for a locale', function () {
    $files = $this->reader->discoverFiles($this->fixturePath, 'en');

    expect($files)->toBeArray()
        ->and($files)->not->toBeEmpty()
        ->and(array_keys($files))->each->not->toContain('.php');
});

it('returns empty array when locale path does not exist', function () {
    $files = $this->reader->discoverFiles($this->fixturePath, 'nonexistent');

    expect($files)->toBe([]);
});

it('discovers vendor files structure', function () {
    $result = $this->reader->discoverVendorFiles($this->fixturePath);

    expect($result)->toBeArray();
});

it('returns empty array for vendor files when no vendor dir exists', function () {
    $tempDir = sys_get_temp_dir().'/php_reader_test_'.uniqid();
    mkdir($tempDir, 0755, true);

    $result = $this->reader->discoverVendorFiles($tempDir);

    @rmdir($tempDir);

    expect($result)->toBe([]);
});

it('discovers vendor files with nested structure', function () {
    $tempDir = sys_get_temp_dir().'/php_reader_vendor_'.uniqid();
    mkdir($tempDir.'/vendor/package-name/en', 0755, true);
    file_put_contents($tempDir.'/vendor/package-name/en/messages.php', '<?php return ["hello" => "world"];');

    $result = $this->reader->discoverVendorFiles($tempDir);

    @unlink($tempDir.'/vendor/package-name/en/messages.php');
    @rmdir($tempDir.'/vendor/package-name/en');
    @rmdir($tempDir.'/vendor/package-name');
    @rmdir($tempDir.'/vendor');
    @rmdir($tempDir);

    expect($result)->toHaveKey('package-name')
        ->and($result['package-name'])->toHaveKey('en')
        ->and($result['package-name']['en'])->toHaveKey('messages');
});

it('rejects path outside allowed base', function () {
    $outsideFile = tempnam(sys_get_temp_dir(), 'outside');
    rename($outsideFile, $outsideFile.'.php');
    file_put_contents($outsideFile.'.php', '<?php return ["key" => "value"];');

    $result = $this->reader->read($outsideFile.'.php', $this->fixturePath);

    @unlink($outsideFile.'.php');

    expect($result)->toBe([]);
});

it('returns empty array when realpath fails', function () {
    $result = $this->reader->read('/nonexistent/symlink/file.php');

    expect($result)->toBe([]);
});

it('returns empty array when file content is not an array', function () {
    $tempDir = sys_get_temp_dir().'/php_reader_noarr_'.uniqid();
    mkdir($tempDir, 0755, true);
    file_put_contents($tempDir.'/string.php', '<?php return "not an array";');

    $result = $this->reader->read($tempDir.'/string.php', $tempDir);

    @unlink($tempDir.'/string.php');
    @rmdir($tempDir);

    expect($result)->toBe([]);
});
