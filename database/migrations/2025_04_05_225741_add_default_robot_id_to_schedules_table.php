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
            $table->unsignedBigInteger('robot_id')->default(1)->change(); // Zet hier de standaard waarde
        });
    }
    
    public function down()
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->unsignedBigInteger('robot_id')->nullable()->change(); // Zet terug naar null als je het wilt terugdraaien
        });
    }
    
};
