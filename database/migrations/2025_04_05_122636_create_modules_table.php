<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['chassis', 'aandrijving', 'wielen', 'stuur', 'stoelen']);
            $table->decimal('cost', 10, 2);
            $table->integer('assembly_time'); // in minuten
            $table->json('specifications'); // voor afmetingen, compatibiliteit etc.
            $table->string('image_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
