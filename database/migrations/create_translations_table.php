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
        Schema::create('ltu_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('language_id');
            $table->boolean('source')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ltu_translations');
    }
};
