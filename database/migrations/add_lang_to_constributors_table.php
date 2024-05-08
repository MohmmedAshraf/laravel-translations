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
        Schema::table('ltu_contributors', function (Blueprint $table) {
            $table->string('lang')->default('en')->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('ltu_contributors', function (Blueprint $table) {
            $table->dropIfExists('lang');
        });
    }
};
