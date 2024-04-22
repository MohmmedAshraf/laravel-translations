<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Outhebox\TranslationsUI\Enums\RoleEnum;

return new class extends Migration
{
    public function getConnection()
    {
        $connection = config('translations.database_connection');

        return $connection ?? $this->connection;
    }

    public function up(): void
    {
        Schema::create('ltu_invites', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('token', 32)->unique();
            $table->tinyInteger('role')->default(RoleEnum::translator->value);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ltu_invites');
    }
};
