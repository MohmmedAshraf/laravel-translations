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
        Schema::table('ltu_translation_files', function (Blueprint $table) {
            $table->boolean('is_root')->default(false)->after('extension');
        });
    }

    public function down(): void
    {
        Schema::table('ltu_translation_files', function (Blueprint $table) {
            $table->dropColumn('is_root');
        });
    }
};
