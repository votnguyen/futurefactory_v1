<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('schedules', function (Blueprint $table) {
        $table->unsignedBigInteger('module_id')->nullable(); // Voeg module_id toe
        $table->foreign('module_id')->references('id')->on('modules')->onDelete('set null'); // Koppelen met de modules tabel
    });
}

public function down()
{
    Schema::table('schedules', function (Blueprint $table) {
        $table->dropColumn('module_id');
    });
}

};
