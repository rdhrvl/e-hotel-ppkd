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
        Schema::table('guests', function (Blueprint $table) {
            $table->string('profession')->nullable()->after('address');
            $table->string('company')->nullable()->after('profession');
            $table->string('nationality')->default('Indonesian')->after('company');
            $table->date('birth_date')->nullable()->after('nationality');
            $table->string('member_no')->nullable()->after('birth_date');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->string('arrival_time')->nullable()->after('number_of_guests');
            $table->string('box_no')->nullable()->after('arrival_time');
            $table->string('box_issued_by')->nullable()->after('box_no');
            $table->date('box_date')->nullable()->after('box_issued_by');
            $table->string('payment_method')->default('cash')->after('box_date');
            $table->text('notes')->nullable()->after('payment_method');
            $table->string('book_by')->nullable()->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->dropColumn(['profession', 'company', 'nationality', 'birth_date', 'member_no']);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['arrival_time', 'box_no', 'box_issued_by', 'box_date', 'payment_method', 'notes', 'book_by']);
        });
    }
};
