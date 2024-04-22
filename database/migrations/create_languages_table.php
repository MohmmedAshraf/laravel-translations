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
        Schema::create('ltu_languages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->index();
            $table->boolean('rtl')->default(false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ltu_languages');
    }
};
