<?php

use Outhebox\Translations\Services\Importer\JsonFileReader;

beforeEach(function () {
    $this->reader = new JsonFileReader;
    $this->fixturePath = __DIR__.'/../../../Fixtures/lang';
});

it('reads a JSON translation file', function () {
    $result = $this->reader->read($this->fixturePath.'/en.json');

    expect($result)->toBeArray()
        ->and($result)->toHaveKey('Hello')
        ->and($result['Hello'])->toBe('Hello');
});

it('returns empty array for missing file', function () {
    $result = $this->reader->read($this->fixturePath.'/nonexistent.json');

    expect($result)->toBeEmpty();
});

it('discovers JSON translation files', function () {
    $files = $this->reader->discoverFiles($this->fixturePath);

    expect($files)->toHaveKeys(['en', 'fr']);
});

it('returns empty for missing directory', function () {
    $files = $this->reader->discoverFiles('/nonexistent/path');

    expect($files)->toBeEmpty();
});

it('reads JSON with parameters', function () {
    $result = $this->reader->read($this->fixturePath.'/en.json');

    expect($result)->toHaveKey('Welcome, :name');
});

it('reads French JSON file', function () {
    $result = $this->reader->read($this->fixturePath.'/fr.json');

    expect($result['Hello'])->toBe('Bonjour')
        ->and($result['Goodbye'])->toBe('Au revoir');
});
