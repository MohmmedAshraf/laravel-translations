<?php

use Outhebox\Translations\Services\Importer\PhpFileReader;

beforeEach(function () {
    $this->reader = new PhpFileReader;
    $this->fixturePath = __DIR__.'/../../../Fixtures/lang';
});

it('reads a PHP translation file', function () {
    $result = $this->reader->read($this->fixturePath.'/en/auth.php', $this->fixturePath);

    expect($result)->toBeArray();
    expect($result)->toHaveKey('failed');
    expect($result['failed'])->toBe('These credentials do not match our records.');
});

it('flattens nested arrays with dot notation', function () {
    $result = $this->reader->read($this->fixturePath.'/en/validation.php', $this->fixturePath);

    expect($result)->toHaveKey('custom.email.required');
    expect($result['custom.email.required'])->toBe('We need your email address.');
});

it('returns empty array for missing file', function () {
    $result = $this->reader->read($this->fixturePath.'/nonexistent.php');

    expect($result)->toBeEmpty();
});

it('discovers locale directories', function () {
    $locales = $this->reader->discoverLocales($this->fixturePath);

    expect($locales)->toContain('en', 'fr', 'ar');
});

it('discovers PHP files for a locale', function () {
    $files = $this->reader->discoverFiles($this->fixturePath, 'en');

    expect($files)->toHaveKeys(['auth', 'messages', 'validation']);
});

it('returns empty for missing locale directory', function () {
    $files = $this->reader->discoverFiles($this->fixturePath, 'nonexistent');

    expect($files)->toBeEmpty();
});

it('returns empty for missing lang path', function () {
    $locales = $this->reader->discoverLocales('/nonexistent/path');

    expect($locales)->toBeEmpty();
});

it('reads file with parameters in values', function () {
    $result = $this->reader->read($this->fixturePath.'/en/messages.php', $this->fixturePath);

    expect($result)->toHaveKey('welcome');
    expect($result['welcome'])->toContain(':name');
});
