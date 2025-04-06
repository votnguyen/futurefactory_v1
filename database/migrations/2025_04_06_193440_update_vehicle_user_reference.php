<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   // In de migratie:
public function up()
{
    Schema::table('vehicles', function (Blueprint $table) {
        // Als je customer_id wilt gebruiken:
        $table->renameColumn('user_id', 'customer_id');
        
        // Of als je user_id wilt gebruiken (standaard Laravel):
        // $table->renameColumn('customer_id', 'user_id');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            //
        });
    }
};
