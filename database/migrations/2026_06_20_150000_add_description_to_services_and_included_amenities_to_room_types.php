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
        Schema::table('services', function (Blueprint $table) {
            $table->text('description')->nullable()->after('price');
        });

        Schema::table('room_types', function (Blueprint $table) {
            $table->json('included_amenities')->nullable()->after('has_breakfast');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('description');
        });

        Schema::table('room_types', function (Blueprint $table) {
            $table->dropColumn('included_amenities');
        });
    }
};
