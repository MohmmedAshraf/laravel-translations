<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function getConnection(): ?string
    {
        return config('translations.database_connection');
    }

    public function up(): void
    {
        Schema::connection($this->getConnection())->create('ltu_languages', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->string('name');
            $table->string('native_name')->nullable();
            $table->boolean('rtl')->default(false);
            $table->boolean('is_source')->default(false);
            $table->string('tone')->default('neutral');
            $table->boolean('active')->default(false);
            $table->timestamps();

            $table->index('active');
        });

        Schema::connection($this->getConnection())->create('ltu_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('namespace')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_format')->default('php');
            $table->timestamps();

            $table->unique(['name', 'namespace']);
        });

        Schema::connection($this->getConnection())->create('ltu_translation_keys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('ltu_groups')->cascadeOnDelete();
            $table->string('key');
            $table->text('context_note')->nullable();
            $table->json('parameters')->nullable();
            $table->boolean('is_html')->default(false);
            $table->boolean('is_plural')->default(false);
            $table->string('priority')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique(['group_id', 'key']);
        });

        Schema::connection($this->getConnection())->create('ltu_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('translation_key_id')->constrained('ltu_translation_keys')->cascadeOnDelete();
            $table->foreignId('language_id')->constrained('ltu_languages')->cascadeOnDelete();
            $table->text('value')->nullable();
            $table->string('status')->default('untranslated');
            $table->boolean('needs_review')->default(false);
            $table->string('translated_by')->nullable();
            $table->string('reviewed_by')->nullable();
            $table->boolean('ai_generated')->default(false);
            $table->string('ai_provider')->nullable();
            $table->timestamps();

            $table->unique(['translation_key_id', 'language_id']);
            $table->index('status');
            $table->index('language_id');
            $table->index('translated_by');
        });

        Schema::connection($this->getConnection())->create('ltu_contributors', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->string('role')->default('translator');
            $table->boolean('is_active')->default(true);
            $table->string('invite_token')->nullable()->unique();
            $table->timestamp('invite_expires_at')->nullable();
            $table->timestamp('last_active_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::connection($this->getConnection())->create('ltu_contributor_language', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('contributor_id')->constrained('ltu_contributors')->cascadeOnDelete();
            $table->foreignId('language_id')->constrained('ltu_languages')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['contributor_id', 'language_id']);
        });

        Schema::connection($this->getConnection())->create('ltu_import_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('locale_count');
            $table->integer('key_count');
            $table->integer('new_count')->default(0);
            $table->integer('updated_count')->default(0);
            $table->integer('duration_ms');
            $table->string('triggered_by')->nullable();
            $table->string('source');
            $table->boolean('fresh')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::connection($this->getConnection())->create('ltu_export_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('locale_count');
            $table->integer('file_count');
            $table->integer('key_count');
            $table->integer('duration_ms');
            $table->string('triggered_by')->nullable();
            $table->string('source');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        $connection = $this->getConnection();

        Schema::connection($connection)->dropIfExists('ltu_export_logs');
        Schema::connection($connection)->dropIfExists('ltu_import_logs');
        Schema::connection($connection)->dropIfExists('ltu_contributor_language');
        Schema::connection($connection)->dropIfExists('ltu_contributors');
        Schema::connection($connection)->dropIfExists('ltu_translations');
        Schema::connection($connection)->dropIfExists('ltu_translation_keys');
        Schema::connection($connection)->dropIfExists('ltu_groups');
        Schema::connection($connection)->dropIfExists('ltu_languages');
    }
};
