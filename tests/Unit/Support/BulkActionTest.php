<?php

use Outhebox\Translations\Support\DataTable\BulkAction;

it('creates a bulk action with make', function () {
    $action = BulkAction::make('delete');

    expect($action->getName())->toBe('delete')
        ->and($action->hasHandler())->toBeFalse()
        ->and($action->getHandler())->toBeNull();
});

it('sets label', function () {
    $array = BulkAction::make('delete')
        ->label('Delete Selected')
        ->toArray();

    expect($array['label'])->toBe('Delete Selected');
});

it('auto-generates label from name when not set', function () {
    $array = BulkAction::make('bulk_delete')->toArray();

    expect($array['label'])->toBe('Bulk Delete');
});

it('sets icon', function () {
    $array = BulkAction::make('delete')
        ->icon('trash')
        ->toArray();

    expect($array['icon'])->toBe('trash');
});

it('sets handler via closure action', function () {
    $handler = fn () => 'handled';

    $action = BulkAction::make('delete')
        ->action($handler);

    expect($action->hasHandler())->toBeTrue()
        ->and($action->getHandler())->toBe($handler);
});

it('sets url via string action starting with slash', function () {
    $array = BulkAction::make('export')
        ->action('/export/csv')
        ->toArray();

    expect($array['url'])->toBe('/export/csv');
});

it('sets url via http string action', function () {
    $array = BulkAction::make('export')
        ->action('https://example.com/export')
        ->toArray();

    expect($array['url'])->toBe('https://example.com/export');
});

it('sets confirm message', function () {
    $array = BulkAction::make('delete')
        ->confirm('Are you sure?', 'Delete Items')
        ->toArray();

    expect($array['confirm'])->toBe('Are you sure?')
        ->and($array['confirmTitle'])->toBe('Delete Items');
});

it('uses default confirm message when null passed', function () {
    $array = BulkAction::make('delete')
        ->confirm()
        ->toArray();

    expect($array['confirm'])->toBe('Are you sure you want to perform this action?')
        ->and($array['confirmTitle'])->toBe('Confirm Action');
});

it('sets destructive variant', function () {
    $array = BulkAction::make('delete')
        ->destructive()
        ->toArray();

    expect($array['variant'])->toBe('destructive');
});

it('defaults to default variant', function () {
    $array = BulkAction::make('export')->toArray();

    expect($array['variant'])->toBe('default');
});

it('sets download flag', function () {
    $array = BulkAction::make('export')
        ->download()
        ->toArray();

    expect($array['download'])->toBeTrue();
});

it('defaults download to false', function () {
    $array = BulkAction::make('export')->toArray();

    expect($array['download'])->toBeFalse();
});

it('sets url directly', function () {
    $action = BulkAction::make('delete');
    $action->setUrl('/custom-url');

    expect($action->toArray()['url'])->toBe('/custom-url');
});

it('converts to array with all fields', function () {
    $array = BulkAction::make('delete')
        ->label('Delete')
        ->icon('trash')
        ->confirm('Sure?', 'Confirm')
        ->destructive()
        ->toArray();

    expect($array)->toHaveKeys(['name', 'label', 'icon', 'url', 'confirm', 'confirmTitle', 'variant', 'download'])
        ->and($array['name'])->toBe('delete')
        ->and($array['label'])->toBe('Delete')
        ->and($array['icon'])->toBe('trash')
        ->and($array['variant'])->toBe('destructive');
});
