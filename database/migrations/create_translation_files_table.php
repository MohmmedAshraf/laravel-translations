<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Outhebox\TranslationsUI\Facades\TranslationsUI;

return new class extends Migration
{
    public function getConnection()
    {
        return TranslationsUI::getConnection() ?? $this->connection;
    }

    public function up(): void
    {
        Schema::create('ltu_translation_files', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('extension');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ltu_translation_files');
    }
};
