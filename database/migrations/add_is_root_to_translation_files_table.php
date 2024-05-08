<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Outhebox\TranslationsUI\Facades\TranslationsUI;

return new class() extends Migration
{
    public function getConnection()
    {
        return TranslationsUI::getConnection() ?? $this->connection;
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
