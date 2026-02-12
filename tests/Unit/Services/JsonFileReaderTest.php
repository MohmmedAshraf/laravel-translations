<?php

use Outhebox\Translations\Services\Importer\JsonFileReader;

beforeEach(function () {
    $this->reader = new JsonFileReader;
});

it('reads a json translation file', function () {
    $tempFile = tempnam(sys_get_temp_dir(), 'json_test');
    file_put_contents($tempFile, json_encode(['hello' => 'world', 'foo' => 'bar']));

    $result = $this->reader->read($tempFile);

    @unlink($tempFile);

    expect($result)->toBe(['hello' => 'world', 'foo' => 'bar']);
});

it('returns empty array for nonexistent file', function () {
    $result = $this->reader->read('/nonexistent/file.json');

    expect($result)->toBe([]);
});

it('returns empty array for invalid json', function () {
    $tempFile = tempnam(sys_get_temp_dir(), 'json_test');
    file_put_contents($tempFile, 'not valid json {{{');

    $result = $this->reader->read($tempFile);

    @unlink($tempFile);

    expect($result)->toBe([]);
});

it('returns empty array for non-array json', function () {
    $tempFile = tempnam(sys_get_temp_dir(), 'json_test');
    file_put_contents($tempFile, '"just a string"');

    $result = $this->reader->read($tempFile);

    @unlink($tempFile);

    expect($result)->toBe([]);
});

it('discovers json files in lang path', function () {
    $tempDir = sys_get_temp_dir().'/json_reader_test_'.uniqid();
    mkdir($tempDir, 0755, true);
    file_put_contents($tempDir.'/en.json', '{}');
    file_put_contents($tempDir.'/fr.json', '{}');
    file_put_contents($tempDir.'/not-json.txt', 'test');

    $result = $this->reader->discoverFiles($tempDir);

    @unlink($tempDir.'/en.json');
    @unlink($tempDir.'/fr.json');
    @unlink($tempDir.'/not-json.txt');
    @rmdir($tempDir);

    expect($result)->toHaveCount(2)
        ->and(array_keys($result))->toBe(['en', 'fr']);
});

it('returns empty array when lang path does not exist', function () {
    $result = $this->reader->discoverFiles('/nonexistent/path');

    expect($result)->toBe([]);
});
