<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ltu_phrases', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('translation_id')->constrained('ltu_translations')->cascadeOnDelete();
            $table->foreignId('translation_file_id')->constrained('ltu_translation_files')->cascadeOnDelete();
            $table->foreignId('phrase_id')->nullable()->constrained('ltu_phrases')->cascadeOnDelete();
            $table->text('key');
            $table->text('group');
            $table->text('value')->nullable();
            $table->json('parameters')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ltu_phrases');
    }
};
