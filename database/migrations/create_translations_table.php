<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Outhebox\TranslationsUI\Models\Language;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ltu_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Language::class)->constrained('ltu_languages')->cascadeOnDelete();
            $table->boolean('source')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ltu_translations');
    }
};
