<?php

use Outhebox\Translations\Concerns\HasDataTable;
use Outhebox\Translations\Models\TranslationKey;
use Outhebox\Translations\Support\DataTable\Column;

it('extracts export values for various types', function () {
    $controller = new class
    {
        use HasDataTable;

        protected function tableModel(): string
        {
            return TranslationKey::class;
        }

        protected function tableColumns(): array
        {
            return [
                Column::make('key', 'Key'),
            ];
        }

        public function testExtract(mixed $row, array $columns): array
        {
            return $this->extractExportValues($row, $columns);
        }
    };

    $columns = [
        ['header' => 'Name', 'key' => 'name'],
        ['header' => 'Date', 'key' => 'date'],
        ['header' => 'Tags', 'key' => 'tags'],
        ['header' => 'Active', 'key' => 'active'],
        ['header' => 'Empty', 'key' => 'empty'],
    ];

    $row = (object) [
        'name' => 'Test',
        'date' => new DateTime('2024-01-15'),
        'tags' => ['php', 'laravel'],
        'active' => true,
        'empty' => null,
    ];

    $result = $controller->testExtract($row, $columns);

    expect($result[0])->toBe('Test')
        ->and($result[1])->toBe('2024-01-15')
        ->and($result[2])->toBe('php, laravel')
        ->and($result[3])->toBe('Yes')
        ->and($result[4])->toBe('');
});

it('extracts export values for false boolean', function () {
    $controller = new class
    {
        use HasDataTable;

        protected function tableModel(): string
        {
            return TranslationKey::class;
        }

        protected function tableColumns(): array
        {
            return [];
        }

        public function testExtract(mixed $row, array $columns): array
        {
            return $this->extractExportValues($row, $columns);
        }
    };

    $row = (object) ['active' => false];
    $columns = [['header' => 'Active', 'key' => 'active']];

    $result = $controller->testExtract($row, $columns);

    expect($result[0])->toBe('No');
});

it('extracts export values for backed enum with HasLabel', function () {
    $controller = new class
    {
        use HasDataTable;

        protected function tableModel(): string
        {
            return TranslationKey::class;
        }

        protected function tableColumns(): array
        {
            return [];
        }

        public function testExtract(mixed $row, array $columns): array
        {
            return $this->extractExportValues($row, $columns);
        }
    };

    $row = (object) ['status' => Outhebox\Translations\Enums\TranslationStatus::Translated];
    $columns = [['header' => 'Status', 'key' => 'status']];

    $result = $controller->testExtract($row, $columns);

    expect($result[0])->toBe('Translated');
});

it('resolves resource name from model class', function () {
    $controller = new class
    {
        use HasDataTable;

        protected function tableModel(): string
        {
            return TranslationKey::class;
        }

        protected function tableColumns(): array
        {
            return [];
        }

        public function getResourceName(): array
        {
            $method = new ReflectionMethod($this, 'resolveResourceName');

            return $method->invoke($this);
        }
    };

    $name = $controller->getResourceName();

    expect($name[0])->toBe('translation key')
        ->and($name[1])->toBe('translation keys');
});
