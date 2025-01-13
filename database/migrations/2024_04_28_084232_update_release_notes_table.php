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
        Schema::table('release_notes', function (Blueprint $table) {
            $table->boolean('inline')->default(false)->after('type');
            $table->string('image')->nullable()->after('inline');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('release_notes', function (Blueprint $table) {
            $table->dropColumn('inline');
            $table->dropColumn('image');
        });
    }
};
