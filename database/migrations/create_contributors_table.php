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
        Schema::create('ltu_contributors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->tinyInteger('role')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ltu_contributors');
    }
};
