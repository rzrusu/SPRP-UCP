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
        Schema::create('release_notes', function (Blueprint $table) {

            $table->id();
            $table->string('author');
            $table->string('slug')->unique();

            $table->enum('type', ['release', 'game', 'ucp']);

            $table->string('title');
            $table->string('description')->nullable();
            $table->text('content');

            $table->text('added')->nullable();
            $table->text('changed')->nullable();
            $table->text('fixed')->nullable();
            $table->text('removed')->nullable();

            $table->string('status')->default('draft');

            $table->timestamp('published_at')->nullable();
            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('release_notes');
    }
};
