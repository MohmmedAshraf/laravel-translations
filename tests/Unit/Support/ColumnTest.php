<?php

use Outhebox\Translations\Enums\ContributorRole;
use Outhebox\Translations\Enums\TranslationStatus;
use Outhebox\Translations\Support\DataTable\Column;

it('creates a column with make', function () {
    $column = Column::make('name', 'Name');

    expect($column->getId())->toBe('name');
    expect($column->toArray()['header'])->toBe('Name');
});

it('auto-generates header from id when not set', function () {
    $array = Column::make('created_at')->toArray();

    expect($array['header'])->toBe('Created At');
});

it('defaults to text type', function () {
    $array = Column::make('name')->toArray();

    expect($array['type'])->toBe('text');
});

it('sets type to mono', function () {
    $array = Column::make('key')->mono()->toArray();

    expect($array['type'])->toBe('mono');
});

it('sets type to date', function () {
    $array = Column::make('created_at')->date()->toArray();

    expect($array['type'])->toBe('date');
});

it('sets type to datetime', function () {
    $array = Column::make('created_at')->datetime()->toArray();

    expect($array['type'])->toBe('datetime');
});

it('sets type to list', function () {
    $array = Column::make('tags')->list()->toArray();

    expect($array['type'])->toBe('list');
});

it('sets type to boolean', function () {
    $array = Column::make('active')->boolean()->toArray();

    expect($array['type'])->toBe('boolean');
});

it('sets type to progress', function () {
    $array = Column::make('completion')->progress()->toArray();

    expect($array['type'])->toBe('progress');
});

it('sets type to badge with enum', function () {
    $column = Column::make('status')->badge(TranslationStatus::class);
    $array = $column->toArray();

    expect($array['type'])->toBe('badge')
        ->and($array['badgeMap'])->toHaveKeys(['untranslated', 'translated', 'needs_review', 'approved'])
        ->and($column->getBadgeEnum())->toBe(TranslationStatus::class);
});

it('sets type to badge with array map', function () {
    $map = ['active' => ['label' => 'Active', 'variant' => 'default']];
    $array = Column::make('status')->badge($map)->toArray();

    expect($array['type'])->toBe('badge')
        ->and($array['badgeMap'])->toBe($map);
});

it('sets type to status-icon with enum', function () {
    $array = Column::make('status')->statusIcon(TranslationStatus::class)->toArray();

    expect($array['type'])->toBe('status-icon')
        ->and($array['badgeMap'])->toHaveKeys(['untranslated', 'translated', 'needs_review', 'approved']);
});

it('sets type to language', function () {
    $array = Column::make('code')->language()->toArray();

    expect($array['type'])->toBe('language');
});

it('sets type to relative-time', function () {
    $array = Column::make('updated_at')->relativeTime()->toArray();

    expect($array['type'])->toBe('relative-time');
});

it('sets sortable', function () {
    $array = Column::make('name')->sortable()->toArray();

    expect($array['sortable'])->toBeTrue();
});

it('sets searchable', function () {
    $array = Column::make('name')->searchable()->toArray();

    expect($array['searchable'])->toBeTrue();
});

it('sets hidden', function () {
    $array = Column::make('name')->hidden()->toArray();

    expect($array['visible'])->toBeFalse();
});

it('sets fixed (cannot hide)', function () {
    $array = Column::make('name')->fixed()->toArray();

    expect($array['canHide'])->toBeFalse();
});

it('sets min and max size', function () {
    $array = Column::make('name')->minSize(100)->maxSize(300)->toArray();

    expect($array['minSize'])->toBe(100);
    expect($array['maxSize'])->toBe(300);
});

it('sets truncate', function () {
    $array = Column::make('value')->truncate(50)->toArray();

    expect($array['truncate'])->toBe(50);
});

it('sets empty value', function () {
    $array = Column::make('notes')->empty('—')->toArray();

    expect($array['emptyValue'])->toBe('—');
});

it('sets accessor key', function () {
    $array = Column::make('group_name')->accessor('group.name')->toArray();

    expect($array['accessorKey'])->toBe('group.name');
});

it('sets centered', function () {
    $array = Column::make('status')->centered()->toArray();

    expect($array['cellClassName'])->toContain('text-center')
        ->and($array['headerClassName'])->toContain('justify-center');
});

it('sets header icon', function () {
    $array = Column::make('status')->headerIcon('check')->toArray();

    expect($array['headerIcon'])->toBe('check');
});

it('sets fill', function () {
    $array = Column::make('value')->fill()->toArray();

    expect($array['fill'])->toBeTrue();
});

it('sets sort column as string', function () {
    $column = Column::make('name')->sortColumn('users.name');

    expect($column->getSortColumn())->toBe('users.name');
});

it('sets sort column as closure', function () {
    $callback = fn () => 'sorted';
    $column = Column::make('name')->sortColumn($callback);

    expect($column->getSortColumn())->toBe($callback);
});

it('sets badge map directly', function () {
    $map = ['active' => ['label' => 'Active']];
    $column = Column::make('status')->setBadgeMap($map);

    expect($column->toArray()['badgeMap'])->toBe($map);
});

it('builds badge map with color variants from enum', function () {
    $array = Column::make('role')->badge(ContributorRole::class)->toArray();

    expect($array['badgeMap']['owner']['color'])->toBe('purple')
        ->and($array['badgeMap']['admin']['color'])->toBe('blue')
        ->and($array['badgeMap']['admin']['variant'])->toBe('secondary');
});

it('does not include optional fields when not set', function () {
    $array = Column::make('name', 'Name')->toArray();

    expect($array)->toHaveKeys(['id', 'header', 'type'])
        ->and($array)->not->toHaveKey('sortable')
        ->and($array)->not->toHaveKey('searchable')
        ->and($array)->not->toHaveKey('minSize')
        ->and($array)->not->toHaveKey('fill');
});
