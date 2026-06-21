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
            $table->string('category')->default('General')->after('type');
            $table->boolean('is_active')->default(true)->after('category');
            $table->string('image_path')->nullable()->after('is_active');
        });

        Schema::create('food_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->string('status')->default('processed'); // 'processed', 'preparing', 'delivered', 'completed'
            $table->decimal('total_price', 10, 2);
            $table->timestamps();
        });

        Schema::create('food_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('food_order_id')->constrained('food_orders')->cascadeOnDelete();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_order_items');
        Schema::dropIfExists('food_orders');

        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['category', 'is_active', 'image_path']);
        });
    }
};
