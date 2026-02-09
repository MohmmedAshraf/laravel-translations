<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

function dropV2Tables(): void
{
    Schema::dropIfExists('ltu_export_logs');
    Schema::dropIfExists('ltu_import_logs');
    Schema::dropIfExists('ltu_contributor_language');
    Schema::dropIfExists('ltu_contributors');
    Schema::dropIfExists('ltu_translations');
    Schema::dropIfExists('ltu_translation_keys');
    Schema::dropIfExists('ltu_groups');
    Schema::dropIfExists('ltu_languages');
}

function createV1Schema(): void
{
    Schema::create('ltu_languages', function ($table) {
        $table->id();
        $table->string('name');
        $table->string('code')->index();
        $table->boolean('rtl')->default(false);
    });

    Schema::create('ltu_translations', function ($table) {
        $table->id();
        $table->foreignId('language_id');
        $table->boolean('source')->default(false);
        $table->timestamps();
    });

    Schema::create('ltu_translation_files', function ($table) {
        $table->id();
        $table->string('name');
        $table->string('extension');
        $table->boolean('is_root')->default(false);
    });

    Schema::create('ltu_phrases', function ($table) {
        $table->id();
        $table->char('uuid', 36);
        $table->foreignId('translation_id')->constrained('ltu_translations')->cascadeOnDelete();
        $table->foreignId('translation_file_id')->constrained('ltu_translation_files')->cascadeOnDelete();
        $table->foreignId('phrase_id')->nullable()->constrained('ltu_phrases')->nullOnDelete();
        $table->text('key');
        $table->string('group');
        $table->text('value')->nullable();
        $table->string('status')->default('active');
        $table->json('parameters')->nullable();
        $table->text('note')->nullable();
        $table->timestamps();
    });

    Schema::create('ltu_contributors', function ($table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->string('password');
        $table->string('avatar')->nullable();
        $table->tinyInteger('role')->nullable();
        $table->rememberToken();
        $table->timestamps();
    });

    Schema::create('ltu_invites', function ($table) {
        $table->id();
        $table->string('email')->unique();
        $table->string('token', 32)->unique();
        $table->tinyInteger('role')->default(2);
        $table->timestamps();
    });
}

function seedV1Data(): void
{
    $now = now();

    DB::table('ltu_languages')->insert([
        ['id' => 1, 'name' => 'English', 'code' => 'en', 'rtl' => false],
        ['id' => 2, 'name' => 'Arabic', 'code' => 'ar', 'rtl' => true],
    ]);

    DB::table('ltu_translations')->insert([
        ['id' => 1, 'language_id' => 1, 'source' => true, 'created_at' => $now, 'updated_at' => $now],
        ['id' => 2, 'language_id' => 2, 'source' => false, 'created_at' => $now, 'updated_at' => $now],
    ]);

    DB::table('ltu_translation_files')->insert([
        ['id' => 1, 'name' => 'auth', 'extension' => 'php', 'is_root' => false],
        ['id' => 2, 'name' => 'messages', 'extension' => 'json', 'is_root' => false],
    ]);

    DB::table('ltu_phrases')->insert([
        // Source phrases (English)
        ['id' => 1, 'uuid' => 'uuid-1', 'translation_id' => 1, 'translation_file_id' => 1, 'phrase_id' => null, 'key' => 'failed', 'group' => 'auth', 'value' => 'These credentials do not match.', 'status' => 'active', 'parameters' => null, 'note' => null, 'created_at' => $now, 'updated_at' => $now],
        ['id' => 2, 'uuid' => 'uuid-2', 'translation_id' => 1, 'translation_file_id' => 1, 'phrase_id' => null, 'key' => 'password', 'group' => 'auth', 'value' => 'The provided password is incorrect.', 'status' => 'active', 'parameters' => null, 'note' => null, 'created_at' => $now, 'updated_at' => $now],
        ['id' => 3, 'uuid' => 'uuid-3', 'translation_id' => 1, 'translation_file_id' => 2, 'phrase_id' => null, 'key' => 'welcome', 'group' => 'messages', 'value' => 'Welcome!', 'status' => 'active', 'parameters' => null, 'note' => null, 'created_at' => $now, 'updated_at' => $now],
        ['id' => 4, 'uuid' => 'uuid-4', 'translation_id' => 1, 'translation_file_id' => 2, 'phrase_id' => null, 'key' => 'greeting', 'group' => 'messages', 'value' => 'Hello, :name!', 'status' => 'active', 'parameters' => json_encode([':name']), 'note' => null, 'created_at' => $now, 'updated_at' => $now],

        // Non-source phrases (Arabic)
        ['id' => 5, 'uuid' => 'uuid-5', 'translation_id' => 2, 'translation_file_id' => 1, 'phrase_id' => 1, 'key' => 'failed', 'group' => 'auth', 'value' => 'بيانات الاعتماد غير متطابقة.', 'status' => 'active', 'parameters' => null, 'note' => null, 'created_at' => $now, 'updated_at' => $now],
        ['id' => 6, 'uuid' => 'uuid-6', 'translation_id' => 2, 'translation_file_id' => 1, 'phrase_id' => 2, 'key' => 'password', 'group' => 'auth', 'value' => null, 'status' => 'active', 'parameters' => null, 'note' => null, 'created_at' => $now, 'updated_at' => $now],
        ['id' => 7, 'uuid' => 'uuid-7', 'translation_id' => 2, 'translation_file_id' => 2, 'phrase_id' => 3, 'key' => 'welcome', 'group' => 'messages', 'value' => 'أهلاً!', 'status' => 'needs_update', 'parameters' => null, 'note' => null, 'created_at' => $now, 'updated_at' => $now],
        ['id' => 8, 'uuid' => 'uuid-8', 'translation_id' => 2, 'translation_file_id' => 2, 'phrase_id' => 4, 'key' => 'greeting', 'group' => 'messages', 'value' => 'مرحباً :name!', 'status' => 'active', 'parameters' => json_encode([':name']), 'note' => null, 'created_at' => $now, 'updated_at' => $now],
    ]);

    DB::table('ltu_contributors')->insert([
        ['id' => 1, 'name' => 'Admin', 'email' => 'admin@example.com', 'password' => '$2y$10$hashed_password_here', 'avatar' => null, 'role' => 1, 'remember_token' => null, 'created_at' => $now, 'updated_at' => $now],
        ['id' => 2, 'name' => 'Translator', 'email' => 'translator@example.com', 'password' => '$2y$10$another_hashed_pass', 'avatar' => null, 'role' => 2, 'remember_token' => null, 'created_at' => $now, 'updated_at' => $now],
    ]);

    DB::table('ltu_invites')->insert([
        ['id' => 1, 'email' => 'pending@example.com', 'token' => 'abc123', 'role' => 2, 'created_at' => $now, 'updated_at' => $now],
    ]);
}

function setupV1Database(): void
{
    dropV2Tables();
    createV1Schema();
    seedV1Data();
}

it('migrates all v1 data to v2 schema', function () {
    setupV1Database();

    $this->artisan('translations:upgrade', ['--force' => true])
        ->assertSuccessful();

    expect(Schema::hasTable('ltu_languages'))->toBeTrue()
        ->and(Schema::hasTable('ltu_groups'))->toBeTrue()
        ->and(Schema::hasTable('ltu_translation_keys'))->toBeTrue()
        ->and(Schema::hasTable('ltu_translations'))->toBeTrue()
        ->and(Schema::hasTable('ltu_contributors'))->toBeTrue()
        ->and(DB::table('ltu_languages')->count())->toBe(2)
        ->and(DB::table('ltu_groups')->count())->toBe(2)
        ->and(DB::table('ltu_translation_keys')->count())->toBe(4)
        ->and(DB::table('ltu_translations')->count())->toBe(8)
        ->and(DB::table('ltu_contributors')->count())->toBe(3);

});

it('detects source language from v1 source translations', function () {
    setupV1Database();

    $this->artisan('translations:upgrade', ['--force' => true])
        ->assertSuccessful();

    $english = DB::table('ltu_languages')->where('code', 'en')->first();
    $arabic = DB::table('ltu_languages')->where('code', 'ar')->first();

    expect($english->is_source)->toBeTruthy()
        ->and((bool) $arabic->is_source)->toBeFalse()
        ->and((bool) $english->active)->toBeTrue()
        ->and((bool) $arabic->active)->toBeTrue();
});

it('maps translation files to groups with correct file format', function () {
    setupV1Database();

    $this->artisan('translations:upgrade', ['--force' => true])
        ->assertSuccessful();

    $authGroup = DB::table('ltu_groups')->where('name', 'auth')->first();
    $messagesGroup = DB::table('ltu_groups')->where('name', 'messages')->first();

    expect($authGroup)->not->toBeNull()
        ->and($authGroup->file_format)->toBe('php')
        ->and($messagesGroup)->not->toBeNull()
        ->and($messagesGroup->file_format)->toBe('json');
});

it('splits phrases into translation keys and translations', function () {
    setupV1Database();

    $this->artisan('translations:upgrade', ['--force' => true])
        ->assertSuccessful();

    $authGroup = DB::table('ltu_groups')->where('name', 'auth')->first();
    $keys = DB::table('ltu_translation_keys')->where('group_id', $authGroup->id)->get();

    expect($keys)->toHaveCount(2)
        ->and($keys->pluck('key')->sort()->values()->all())->toBe(['failed', 'password']);

    $failedKey = $keys->firstWhere('key', 'failed');
    $translations = DB::table('ltu_translations')->where('translation_key_id', $failedKey->id)->get();

    expect($translations)->toHaveCount(2);
});

it('maps status correctly', function () {
    setupV1Database();

    $this->artisan('translations:upgrade', ['--force' => true])
        ->assertSuccessful();

    $messagesGroup = DB::table('ltu_groups')->where('name', 'messages')->first();
    $welcomeKey = DB::table('ltu_translation_keys')
        ->where('group_id', $messagesGroup->id)
        ->where('key', 'welcome')
        ->first();

    $arabic = DB::table('ltu_languages')->where('code', 'ar')->first();
    $english = DB::table('ltu_languages')->where('code', 'en')->first();

    $arTranslation = DB::table('ltu_translations')
        ->where('translation_key_id', $welcomeKey->id)
        ->where('language_id', $arabic->id)
        ->first();

    expect($arTranslation->status)->toBe('needs_review')
        ->and((bool) $arTranslation->needs_review)->toBeTrue();

    $enTranslation = DB::table('ltu_translations')
        ->where('translation_key_id', $welcomeKey->id)
        ->where('language_id', $english->id)
        ->first();

    expect($enTranslation->status)->toBe('translated');
});

it('maps null values to untranslated status', function () {
    setupV1Database();

    $this->artisan('translations:upgrade', ['--force' => true])
        ->assertSuccessful();

    $authGroup = DB::table('ltu_groups')->where('name', 'auth')->first();
    $passwordKey = DB::table('ltu_translation_keys')
        ->where('group_id', $authGroup->id)
        ->where('key', 'password')
        ->first();

    $arabic = DB::table('ltu_languages')->where('code', 'ar')->first();

    $translation = DB::table('ltu_translations')
        ->where('translation_key_id', $passwordKey->id)
        ->where('language_id', $arabic->id)
        ->first();

    expect($translation->status)->toBe('untranslated')
        ->and($translation->value)->toBeNull();
});

it('maps contributor roles and generates ULIDs', function () {
    setupV1Database();

    $this->artisan('translations:upgrade', ['--force' => true])
        ->assertSuccessful();

    $admin = DB::table('ltu_contributors')->where('email', 'admin@example.com')->first();
    $translator = DB::table('ltu_contributors')->where('email', 'translator@example.com')->first();

    expect($admin->role)->toBe('owner')
        ->and($translator->role)->toBe('translator')
        ->and(strlen($admin->id))->toBe(26)
        ->and(strlen($translator->id))->toBe(26)
        ->and($admin->id)->not->toBe($translator->id);
});

it('preserves password hashes without re-hashing', function () {
    setupV1Database();

    $this->artisan('translations:upgrade', ['--force' => true])
        ->assertSuccessful();

    $admin = DB::table('ltu_contributors')->where('email', 'admin@example.com')->first();

    expect($admin->password)->toBe('$2y$10$hashed_password_here');
});

it('migrates invites as inactive contributors', function () {
    setupV1Database();

    $this->artisan('translations:upgrade', ['--force' => true])
        ->assertSuccessful();

    $invited = DB::table('ltu_contributors')->where('email', 'pending@example.com')->first();

    expect($invited)->not->toBeNull()
        ->and((bool) $invited->is_active)->toBeFalse()
        ->and($invited->invite_token)->toBe('abc123')
        ->and($invited->role)->toBe('translator');
});

it('warns and succeeds when already migrated', function () {
    $this->artisan('translations:upgrade', ['--force' => true])
        ->assertSuccessful();
});

it('warns and succeeds when no v1 tables exist', function () {
    $this->artisan('translations:upgrade', ['--force' => true])
        ->assertSuccessful();
});

it('cleans up legacy tables with --cleanup flag', function () {
    setupV1Database();

    $this->artisan('translations:upgrade', ['--force' => true, '--cleanup' => true])
        ->assertSuccessful();

    expect(Schema::hasTable('ltu_phrases'))->toBeFalse()
        ->and(Schema::hasTable('ltu_translation_files'))->toBeFalse()
        ->and(Schema::hasTable('ltu_invites'))->toBeFalse();
});

it('preserves v1-only tables without --cleanup flag', function () {
    setupV1Database();

    $this->artisan('translations:upgrade', ['--force' => true])
        ->assertSuccessful();

    expect(Schema::hasTable('ltu_phrases'))->toBeTrue()
        ->and(Schema::hasTable('ltu_translation_files'))->toBeTrue();
});

it('handles empty v1 database', function () {
    dropV2Tables();
    createV1Schema();

    $this->artisan('translations:upgrade', ['--force' => true])
        ->assertSuccessful();

    expect(DB::table('ltu_languages')->count())->toBe(0)
        ->and(DB::table('ltu_groups')->count())->toBe(0)
        ->and(DB::table('ltu_translation_keys')->count())->toBe(0)
        ->and(DB::table('ltu_translations')->count())->toBe(0)
        ->and(DB::table('ltu_contributors')->count())->toBe(0);
});

it('creates translation keys for orphan phrases', function () {
    dropV2Tables();
    createV1Schema();

    $now = now();

    DB::table('ltu_languages')->insert([
        ['id' => 1, 'name' => 'English', 'code' => 'en', 'rtl' => false],
        ['id' => 2, 'name' => 'French', 'code' => 'fr', 'rtl' => false],
    ]);

    DB::table('ltu_translations')->insert([
        ['id' => 1, 'language_id' => 1, 'source' => true, 'created_at' => $now, 'updated_at' => $now],
        ['id' => 2, 'language_id' => 2, 'source' => false, 'created_at' => $now, 'updated_at' => $now],
    ]);

    DB::table('ltu_translation_files')->insert([
        ['id' => 1, 'name' => 'auth', 'extension' => 'php', 'is_root' => false],
    ]);

    // Only non-source phrase exists (orphan — no matching source phrase for this key)
    DB::table('ltu_phrases')->insert([
        ['id' => 1, 'uuid' => 'uuid-1', 'translation_id' => 2, 'translation_file_id' => 1, 'phrase_id' => null, 'key' => 'orphan_key', 'group' => 'auth', 'value' => 'Orphelin', 'status' => 'active', 'parameters' => null, 'note' => null, 'created_at' => $now, 'updated_at' => $now],
    ]);

    $this->artisan('translations:upgrade', ['--force' => true])
        ->assertSuccessful();

    $authGroup = DB::table('ltu_groups')->where('name', 'auth')->first();
    $key = DB::table('ltu_translation_keys')
        ->where('group_id', $authGroup->id)
        ->where('key', 'orphan_key')
        ->first();

    expect($key)->not->toBeNull();

    $translation = DB::table('ltu_translations')
        ->where('translation_key_id', $key->id)
        ->first();

    expect($translation->value)->toBe('Orphelin')
        ->and($translation->status)->toBe('translated');
});

it('preserves parameters from v1 phrases', function () {
    setupV1Database();

    $this->artisan('translations:upgrade', ['--force' => true])
        ->assertSuccessful();

    $messagesGroup = DB::table('ltu_groups')->where('name', 'messages')->first();
    $greetingKey = DB::table('ltu_translation_keys')
        ->where('group_id', $messagesGroup->id)
        ->where('key', 'greeting')
        ->first();

    expect(json_decode($greetingKey->parameters, true))->toBe([':name']);
});

it('fails when database is in a partial state', function () {
    setupV1Database();

    // Manually create a v2 table while v1 tables still exist
    Schema::create('ltu_translation_keys', function ($table) {
        $table->id();
        $table->foreignId('group_id');
        $table->string('key');
        $table->timestamps();
    });

    $this->artisan('translations:upgrade', ['--force' => true])
        ->assertFailed();
});

it('sets rtl correctly for languages', function () {
    setupV1Database();

    $this->artisan('translations:upgrade', ['--force' => true])
        ->assertSuccessful();

    $english = DB::table('ltu_languages')->where('code', 'en')->first();
    $arabic = DB::table('ltu_languages')->where('code', 'ar')->first();

    expect((bool) $english->rtl)->toBeFalse()
        ->and((bool) $arabic->rtl)->toBeTrue();
});
