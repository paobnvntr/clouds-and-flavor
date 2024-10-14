<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('orders_add_on', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['cart_id']);
            // Drop the cart_id column
            $table->dropColumn('cart_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('orders_add_on', function (Blueprint $table) {
            // Add the cart_id column back
            $table->unsignedBigInteger('cart_id')->nullable();
            // Re-add the foreign key constraint
            $table->foreign('cart_id')->references('id')->on('carts')->onDelete('cascade');
        });
    }
};
