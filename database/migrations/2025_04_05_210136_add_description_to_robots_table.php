<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDescriptionToRobotsTable extends Migration
{
    public function up()
    {
        Schema::table('robots', function (Blueprint $table) {
            $table->string('description')->nullable(); // Voeg de description kolom toe
        });
    }

    public function down()
    {
        Schema::table('robots', function (Blueprint $table) {
            $table->dropColumn('description'); // Verwijder de description kolom bij terugdraaien
        });
    }
}
