<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            // Verwijder eerst bestaande foreign key constraint als die bestaat
            $table->dropForeign(['customer_id']);
            
            // Migreer data van user_id naar customer_id
            if (Schema::hasColumn('vehicles', 'user_id') && Schema::hasColumn('vehicles', 'customer_id')) {
                \DB::statement('UPDATE vehicles SET customer_id = user_id WHERE customer_id IS NULL OR customer_id = 0');
            }
            
            // Verwijder user_id kolom
            if (Schema::hasColumn('vehicles', 'user_id')) {
                $table->dropColumn('user_id');
            }
            
            // Voeg nieuwe foreign key toe met unieke naam
            $table->foreign('customer_id', 'fk_vehicles_customer_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            // Verwijder de aangepaste foreign key
            $table->dropForeign('fk_vehicles_customer_id');
            
            // Voeg user_id kolom terug
            $table->unsignedBigInteger('user_id')->nullable();
            
            // Migreer data terug
            \DB::statement('UPDATE vehicles SET user_id = customer_id');
            
            // Voeg foreign key voor user_id terug
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }
};