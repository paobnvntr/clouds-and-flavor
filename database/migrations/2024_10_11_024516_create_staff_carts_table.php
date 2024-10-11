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
        Schema::create('staff_carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('users')->onDelete('cascade'); // Reference to the staff user
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // Reference to the product
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2); // Product price at the time of adding to cart
            $table->string('customer_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_carts');
    }
};
