<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ltu_contributor_languages', function (Blueprint $table) {
            $table->foreignUuid('contributor_id')->constrained('ltu_contributors')->cascadeOnDelete();
            $table->foreignId('language_id')->constrained('ltu_languages')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ltu_contributor_languages');
    }
};
