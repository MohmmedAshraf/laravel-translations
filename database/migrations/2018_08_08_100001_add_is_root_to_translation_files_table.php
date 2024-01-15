<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ltu_translation_files', function (Blueprint $table) {
            $table->boolean('is_root')->default(false)->after('extension');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ltu_translation_files', function (Blueprint $table) {
            $table->dropColumn('is_root');
        });
    }
};
