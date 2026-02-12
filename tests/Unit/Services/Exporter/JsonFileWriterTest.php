<?php

use Outhebox\Translations\Services\Exporter\JsonFileWriter;

beforeEach(function () {
    $this->writer = new JsonFileWriter;
    $this->tempDir = sys_get_temp_dir().'/translations_test_json_'.uniqid();
    mkdir($this->tempDir, 0755, true);
});

afterEach(function () {
    $files = glob($this->tempDir.'/*');
    foreach ($files as $file) {
        unlink($file);
    }
    rmdir($this->tempDir);
});

it('writes a JSON translation file', function () {
    $translations = ['Hello' => 'Bonjour', 'Goodbye' => 'Au revoir'];
    $filePath = $this->tempDir.'/fr.json';

    $result = $this->writer->write($filePath, $translations);

    expect($result)->toBeTrue();

    $content = json_decode(file_get_contents($filePath), true);
    expect($content)->toEqual($translations);
});

it('preserves unicode characters', function () {
    $translations = ['greeting' => 'こんにちは'];
    $filePath = $this->tempDir.'/ja.json';

    $this->writer->write($filePath, $translations, unescapeUnicode: true);

    $raw = file_get_contents($filePath);
    expect($raw)->toContain('こんにちは');
});

it('sorts keys alphabetically', function () {
    $translations = ['Zebra' => 'Z', 'Apple' => 'A'];
    $filePath = $this->tempDir.'/sorted.json';

    $this->writer->write($filePath, $translations, sortKeys: true);

    $content = json_decode(file_get_contents($filePath), true);
    $keys = array_keys($content);

    expect($keys)->toBe(['Apple', 'Zebra']);
});

it('pretty prints JSON', function () {
    $translations = ['Hello' => 'World'];
    $filePath = $this->tempDir.'/pretty.json';

    $this->writer->write($filePath, $translations, prettyPrint: true);

    $raw = file_get_contents($filePath);
    expect($raw)->toContain("\n");
});
