<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ltu_languages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->index();
            $table->boolean('rtl')->default(false);
        });

        Schema::create('ltu_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('language_id')->constrained('ltu_languages')->cascadeOnDelete();
            $table->boolean('source')->default(false);
            $table->timestamps();
        });

        Schema::create('ltu_translation_files', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('extension');
        });

        Schema::create('ltu_phrases', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ltu_phrases');
        Schema::dropIfExists('ltu_translation_files');
        Schema::dropIfExists('ltu_translations');
        Schema::dropIfExists('ltu_languages');
    }
};
