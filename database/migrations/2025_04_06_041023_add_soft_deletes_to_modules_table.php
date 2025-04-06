<?php

// database/migrations/[timestamp]_add_soft_deletes_to_modules_table.php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up()
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};