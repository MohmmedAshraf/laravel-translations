<?php

use Illuminate\Support\Facades\Schema;

it('creates the ltu_languages table', function () {
    expect(Schema::hasTable('ltu_languages'))->toBeTrue();
    expect(Schema::hasColumns('ltu_languages', [
        'id', 'code', 'name', 'native_name', 'rtl', 'active', 'is_source', 'created_at', 'updated_at',
    ]))->toBeTrue();
});

it('creates the ltu_groups table', function () {
    expect(Schema::hasTable('ltu_groups'))->toBeTrue();
    expect(Schema::hasColumns('ltu_groups', [
        'id', 'name', 'namespace', 'file_format', 'created_at', 'updated_at',
    ]))->toBeTrue();
});

it('creates the ltu_translation_keys table', function () {
    expect(Schema::hasTable('ltu_translation_keys'))->toBeTrue();
    expect(Schema::hasColumns('ltu_translation_keys', [
        'id', 'group_id', 'key', 'parameters', 'is_html', 'is_plural', 'created_at', 'updated_at',
    ]))->toBeTrue();
});

it('creates the ltu_translations table', function () {
    expect(Schema::hasTable('ltu_translations'))->toBeTrue();
    expect(Schema::hasColumns('ltu_translations', [
        'id', 'translation_key_id', 'language_id', 'value', 'status', 'created_at', 'updated_at',
    ]))->toBeTrue();
});

it('creates the ltu_contributors table', function () {
    expect(Schema::hasTable('ltu_contributors'))->toBeTrue();
    expect(Schema::hasColumns('ltu_contributors', [
        'id', 'name', 'email', 'password', 'role', 'is_active', 'remember_token', 'created_at', 'updated_at',
    ]))->toBeTrue();
});

it('creates the ltu_contributor_language pivot table', function () {
    expect(Schema::hasTable('ltu_contributor_language'))->toBeTrue();
    expect(Schema::hasColumns('ltu_contributor_language', [
        'id', 'contributor_id', 'language_id', 'created_at', 'updated_at',
    ]))->toBeTrue();
});

it('creates the ltu_import_logs table', function () {
    expect(Schema::hasTable('ltu_import_logs'))->toBeTrue();
    expect(Schema::hasColumns('ltu_import_logs', [
        'id', 'locale_count', 'key_count', 'new_count', 'updated_count',
        'duration_ms', 'triggered_by', 'source', 'fresh', 'notes', 'created_at', 'updated_at',
    ]))->toBeTrue();
});

it('creates the ltu_export_logs table', function () {
    expect(Schema::hasTable('ltu_export_logs'))->toBeTrue();
    expect(Schema::hasColumns('ltu_export_logs', [
        'id', 'locale_count', 'file_count', 'key_count', 'duration_ms',
        'triggered_by', 'source', 'notes', 'created_at', 'updated_at',
    ]))->toBeTrue();
});
