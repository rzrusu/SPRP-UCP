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
        Schema::create('skin_data', function (Blueprint $table) {
            $table->integer('skin_id')->unsigned()->primary();
            $table->string('skin_model_name');
            $table->string('name');
            $table->enum('gender', ['male', 'female']);
            $table->enum('race', ['white', 'black', 'hispanic','asian', 'other']);
            $table->string('gang');
            $table->boolean('usable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skin_data');
    }
};
