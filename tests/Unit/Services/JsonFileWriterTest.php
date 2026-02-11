<?php

use Outhebox\Translations\Services\Exporter\JsonFileWriter;

beforeEach(function () {
    $this->writer = new JsonFileWriter;
    $this->tempDir = sys_get_temp_dir().'/json_writer_test_'.uniqid();
});

afterEach(function () {
    $tempFile = $this->tempDir.'/output.json';
    @unlink($tempFile);
    @rmdir($this->tempDir);
});

it('writes json file with sorted keys', function () {
    mkdir($this->tempDir, 0755, true);
    $filePath = $this->tempDir.'/output.json';

    $result = $this->writer->write($filePath, ['z_key' => 'last', 'a_key' => 'first']);

    expect($result)->toBeTrue()
        ->and(file_exists($filePath))->toBeTrue();

    $content = json_decode(file_get_contents($filePath), true);
    $keys = array_keys($content);
    expect($keys[0])->toBe('a_key')
        ->and($keys[1])->toBe('z_key');
});

it('writes json file without sorted keys', function () {
    mkdir($this->tempDir, 0755, true);
    $filePath = $this->tempDir.'/output.json';

    $this->writer->write($filePath, ['z_key' => 'last', 'a_key' => 'first'], sortKeys: false);

    $content = json_decode(file_get_contents($filePath), true);
    $keys = array_keys($content);
    expect($keys[0])->toBe('z_key');
});

it('creates directory if it does not exist', function () {
    $filePath = $this->tempDir.'/output.json';

    expect(is_dir($this->tempDir))->toBeFalse();

    $this->writer->write($filePath, ['key' => 'value']);

    expect(is_dir($this->tempDir))->toBeTrue()
        ->and(file_exists($filePath))->toBeTrue();
});

it('writes with unicode characters unescaped', function () {
    mkdir($this->tempDir, 0755, true);
    $filePath = $this->tempDir.'/output.json';

    $this->writer->write($filePath, ['greeting' => 'مرحبا']);

    $content = file_get_contents($filePath);
    expect($content)->toContain('مرحبا');
});
