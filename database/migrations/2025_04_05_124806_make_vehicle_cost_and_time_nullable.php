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
        Schema::table('vehicles', function (Blueprint $table) {
            $table->decimal('total_cost', 10, 2)->nullable()->change();
            $table->integer('total_assembly_time')->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->decimal('total_cost', 10, 2)->nullable(false)->change();
            $table->integer('total_assembly_time')->nullable(false)->change();
        });
    }
};
