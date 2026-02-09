<?php

use Outhebox\Translations\Services\Exporter\PhpFileWriter;

beforeEach(function () {
    $this->writer = new PhpFileWriter;
    $this->tempDir = sys_get_temp_dir().'/translations_test_'.uniqid();
    mkdir($this->tempDir, 0755, true);
});

afterEach(function () {
    $files = glob($this->tempDir.'/*');
    foreach ($files as $file) {
        unlink($file);
    }
    rmdir($this->tempDir);
});

it('writes a PHP translation file', function () {
    $translations = ['greeting' => 'Hello', 'farewell' => 'Goodbye'];
    $filePath = $this->tempDir.'/messages.php';

    $result = $this->writer->write($filePath, $translations);

    expect($result)->toBeTrue()
        ->and(file_exists($filePath))->toBeTrue();

    $content = require $filePath;
    expect($content)->toEqual($translations);
});

it('unflattens dot notation keys', function () {
    $dotted = [
        'auth.failed' => 'Failed',
        'auth.throttle' => 'Throttled',
        'validation.required' => 'Required',
    ];

    $result = $this->writer->unflatten($dotted);

    expect($result)->toHaveKey('auth')
        ->and($result['auth'])->toBe(['failed' => 'Failed', 'throttle' => 'Throttled'])
        ->and($result['validation'])->toBe(['required' => 'Required']);
});

it('writes nested arrays correctly', function () {
    $translations = [
        'custom.email.required' => 'Email is required',
        'custom.name.required' => 'Name is required',
    ];
    $filePath = $this->tempDir.'/validation.php';

    $this->writer->write($filePath, $translations);

    $content = require $filePath;
    expect($content['custom']['email']['required'])->toBe('Email is required')
        ->and($content['custom']['name']['required'])->toBe('Name is required');
});

it('sorts keys alphabetically', function () {
    $translations = ['zebra' => 'Z', 'alpha' => 'A', 'beta' => 'B'];
    $filePath = $this->tempDir.'/sorted.php';

    $this->writer->write($filePath, $translations, sortKeys: true);

    $content = require $filePath;
    $keys = array_keys($content);

    expect($keys)->toBe(['alpha', 'beta', 'zebra']);
});

it('creates directories recursively', function () {
    $filePath = $this->tempDir.'/nested/dir/messages.php';

    $result = $this->writer->write($filePath, ['key' => 'value']);

    expect($result)->toBeTrue()
        ->and(file_exists($filePath))->toBeTrue();

    unlink($filePath);
    rmdir($this->tempDir.'/nested/dir');
    rmdir($this->tempDir.'/nested');
});

it('handles empty translations', function () {
    $filePath = $this->tempDir.'/empty.php';

    $result = $this->writer->write($filePath, []);

    expect($result)->toBeTrue();

    $content = require $filePath;
    expect($content)->toBeEmpty();
});
