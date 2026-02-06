<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function getConnection()
    {
        $connection = config('translations.database_connection');

        return $connection ?? $this->connection;
    }

    public function up(): void
    {
        Schema::table('ltu_phrases', function (Blueprint $table) {
            $table->text('key')->change();
        });

        Schema::table('ltu_phrases', function (Blueprint $table) {
            $table->dropForeign(['phrase_id']);
            $table->foreign('phrase_id')
                ->references('id')
                ->on('ltu_phrases')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('ltu_phrases', function (Blueprint $table) {
            $table->dropForeign(['phrase_id']);
            $table->foreign('phrase_id')
                ->references('id')
                ->on('ltu_phrases')
                ->cascadeOnDelete();
        });

        Schema::table('ltu_phrases', function (Blueprint $table) {
            $table->string('key')->change();
        });
    }
};
