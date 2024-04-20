<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Outhebox\TranslationsUI\Enums\StatusEnum;
use Outhebox\TranslationsUI\Models\Phrase;
use Outhebox\TranslationsUI\Models\Translation;
use Outhebox\TranslationsUI\Models\TranslationFile;

return new class extends Migration
{
    public function getConnection()
    {
        $connection = config('translations.database_connection');

        return $connection ?? $this->connection;
    }

    public function up(): void
    {
        Schema::create('ltu_phrases', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignIdFor(Translation::class)->constrained('ltu_translations')->cascadeOnDelete();
            $table->foreignIdFor(TranslationFile::class)->constrained('ltu_translation_files')->cascadeOnDelete();
            $table->foreignIdFor(Phrase::class)->nullable()->constrained('ltu_phrases')->cascadeOnDelete();
            $table->string('key');
            $table->string('group');
            $table->text('value')->nullable();
            $table->string('status')->default(StatusEnum::active->value);
            $table->json('parameters')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ltu_phrases');
    }
};
