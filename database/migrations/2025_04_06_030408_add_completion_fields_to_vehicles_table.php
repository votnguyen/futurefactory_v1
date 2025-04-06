<?php

// database/migrations/[timestamp]_add_completion_fields_to_vehicles_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('completion_status')
                  ->default('concept')
                  ->after('status');
                  
            $table->integer('completion_percentage')
                  ->default(0)
                  ->after('completion_status');
                  
            $table->dateTime('expected_delivery')
                  ->nullable()
                  ->after('completion_percentage');
        });
    }

    public function down()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn([
                'completion_status',
                'completion_percentage',
                'expected_delivery'
            ]);
        });
    }
};