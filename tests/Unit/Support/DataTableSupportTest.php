<?php

use Outhebox\Translations\Enums\ContributorRole;
use Outhebox\Translations\Enums\LanguageStatus;
use Outhebox\Translations\Enums\TranslationStatus;
use Outhebox\Translations\Models\Group;
use Outhebox\Translations\Support\DataTable\BulkAction;
use Outhebox\Translations\Support\DataTable\Column;
use Outhebox\Translations\Support\DataTable\Filter;

// ── BulkAction ──

it('creates bulk action with named route string action', function () {
    $action = BulkAction::make('test')
        ->label('Test')
        ->icon('check')
        ->action('/custom-url')
        ->confirm('Are you sure?', 'Confirm')
        ->destructive()
        ->download();

    $array = $action->toArray();

    expect($array['name'])->toBe('test')
        ->and($array['label'])->toBe('Test')
        ->and($array['icon'])->toBe('check')
        ->and($array['url'])->toBe('/custom-url')
        ->and($array['confirm'])->toBe('Are you sure?')
        ->and($array['confirmTitle'])->toBe('Confirm')
        ->and($array['variant'])->toBe('destructive')
        ->and($array['download'])->toBeTrue();
});

it('creates bulk action with http url', function () {
    $action = BulkAction::make('export')
        ->action('http://example.com/export');

    expect($action->toArray()['url'])->toBe('http://example.com/export');
});

it('creates bulk action with closure handler', function () {
    $handler = fn ($records) => $records;
    $action = BulkAction::make('process')
        ->action($handler);

    expect($action->hasHandler())->toBeTrue()
        ->and($action->getHandler())->toBe($handler);
});

it('creates bulk action without handler', function () {
    $action = BulkAction::make('view');

    expect($action->hasHandler())->toBeFalse()
        ->and($action->getHandler())->toBeNull();
});

it('defaults confirm title when not provided', function () {
    $action = BulkAction::make('delete')->confirm();

    $array = $action->toArray();
    expect($array['confirm'])->toBe('Are you sure you want to perform this action?')
        ->and($array['confirmTitle'])->toBe('Confirm Action');
});

it('generates label from name when not set', function () {
    $action = BulkAction::make('bulk_delete');

    expect($action->toArray()['label'])->toBe('Bulk Delete');
});

// ── Column ──

it('creates text column', function () {
    $column = Column::make('name', 'Name')->text();

    expect($column->toArray()['type'])->toBe('text');
});

it('creates mono column', function () {
    $column = Column::make('code', 'Code')->mono();

    expect($column->toArray()['type'])->toBe('mono');
});

it('creates date column', function () {
    $column = Column::make('created_at', 'Created')->date();

    expect($column->toArray()['type'])->toBe('date');
});

it('creates datetime column', function () {
    $column = Column::make('created_at', 'Created')->datetime();

    expect($column->toArray()['type'])->toBe('datetime');
});

it('creates list column', function () {
    $column = Column::make('tags', 'Tags')->list();

    expect($column->toArray()['type'])->toBe('list');
});

it('creates boolean column', function () {
    $column = Column::make('active', 'Active')->boolean();

    expect($column->toArray()['type'])->toBe('boolean');
});

it('creates progress column', function () {
    $column = Column::make('progress', 'Progress')->progress();

    expect($column->toArray()['type'])->toBe('progress');
});

it('creates language column', function () {
    $column = Column::make('lang', 'Language')->language();

    expect($column->toArray()['type'])->toBe('language');
});

it('creates relative-time column', function () {
    $column = Column::make('updated', 'Updated')->relativeTime();

    expect($column->toArray()['type'])->toBe('relative-time');
});

it('creates status icon column with array map', function () {
    $map = [
        'active' => ['variant' => 'default', 'label' => 'Active'],
        'inactive' => ['variant' => 'outline', 'label' => 'Inactive'],
    ];

    $column = Column::make('status', 'Status')->statusIcon($map);

    $array = $column->toArray();
    expect($array['type'])->toBe('status-icon')
        ->and($array['badgeMap'])->toBe($map);
});

it('creates badge column with enum', function () {
    $column = Column::make('status', 'Status')->badge(TranslationStatus::class);

    $array = $column->toArray();
    expect($array['type'])->toBe('badge')
        ->and($array['badgeMap'])->toBeArray()
        ->and($column->getBadgeEnum())->toBe(TranslationStatus::class);
});

it('creates badge column with array map', function () {
    $map = ['yes' => ['label' => 'Yes']];
    $column = Column::make('flag', 'Flag')->badge($map);

    expect($column->toArray()['badgeMap'])->toBe($map);
});

it('applies column options correctly', function () {
    $column = Column::make('name', 'Name')
        ->sortable()
        ->searchable()
        ->hidden()
        ->fixed()
        ->minSize(100)
        ->maxSize(300)
        ->truncate(50)
        ->empty('N/A')
        ->accessor('full_name')
        ->fill()
        ->headerIcon('user')
        ->centered();

    $array = $column->toArray();

    expect($array['sortable'])->toBeTrue()
        ->and($array['searchable'])->toBeTrue()
        ->and($array['visible'])->toBeFalse()
        ->and($array['canHide'])->toBeFalse()
        ->and($array['minSize'])->toBe(100)
        ->and($array['maxSize'])->toBe(300)
        ->and($array['truncate'])->toBe(50)
        ->and($array['emptyValue'])->toBe('N/A')
        ->and($array['accessorKey'])->toBe('full_name')
        ->and($array['fill'])->toBeTrue()
        ->and($array['headerIcon'])->toBe('user')
        ->and($array['cellClassName'])->toContain('text-center')
        ->and($array['headerClassName'])->toContain('justify-center');
});

it('sets sort column as string', function () {
    $column = Column::make('name', 'Name')->sortColumn('users.name');

    expect($column->getSortColumn())->toBe('users.name');
});

it('sets sort column as closure', function () {
    $fn = fn ($query, $desc) => $query->orderBy('name', $desc ? 'desc' : 'asc');
    $column = Column::make('name', 'Name')->sortColumn($fn);

    expect($column->getSortColumn())->toBe($fn);
});

it('sets badge map directly', function () {
    $map = ['a' => ['label' => 'A']];
    $column = Column::make('test')->setBadgeMap($map);

    expect($column->toArray()['badgeMap'])->toBe($map);
});

it('generates header from id when not provided', function () {
    $column = Column::make('first_name');

    expect($column->toArray()['header'])->toBe('First Name');
});

// ── Filter ──

it('creates filter with enum options', function () {
    $filter = Filter::make('role', ContributorRole::class)->label('Role')->icon('shield');

    $array = $filter->toArray();

    expect($array['key'])->toBe('role')
        ->and($array['label'])->toBe('Role')
        ->and($array['icon'])->toBe('shield')
        ->and($array['options'])->toBeArray()
        ->and(count($array['options']))->toBeGreaterThan(0);
});

it('creates searchable filter', function () {
    $filter = Filter::make('name')->searchable();

    expect($filter->toArray()['searchable'])->toBeTrue();
});

it('creates filter with manual options', function () {
    $options = [
        ['value' => '1', 'label' => 'Yes'],
        ['value' => '0', 'label' => 'No'],
    ];
    $filter = Filter::make('active')->options($options);

    expect($filter->toArray()['options'])->toBe($options);
});

it('creates filter from model query', function () {
    $group = Group::factory()->create(['name' => 'auth']);

    $filter = Filter::make('group_id')
        ->fromModel(Group::class, 'name', 'id');

    $options = $filter->toArray()['options'];
    expect($options)->toHaveCount(1)
        ->and($options[0]['label'])->toBe('auth');
});

it('creates filter from model with closure label', function () {
    $group = Group::factory()->create(['name' => 'auth']);

    $filter = Filter::make('group_id')
        ->fromModel(Group::class, fn ($model) => strtoupper($model->name));

    $options = $filter->toArray()['options'];
    expect($options[0]['label'])->toBe('AUTH');
});

it('generates label from key when not provided', function () {
    $filter = Filter::make('status_code');

    expect($filter->toArray()['label'])->toBe('Status Code');
});

it('builds options with color from enum', function () {
    $filter = Filter::make('status', LanguageStatus::class);

    $options = $filter->toArray()['options'];
    $hasIconColor = collect($options)->some(fn ($opt) => isset($opt['iconColor']));

    expect($hasIconColor)->toBeTrue();
});
