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
        // First, drop existing foreign key constraints if they exist
        Schema::table('purchase_order_items', function (Blueprint $table) {
            // This will drop any existing foreign key constraints
            $table->dropForeign(['purchase_order_id']);
            $table->dropForeign(['product_id']);
        });

        // Then add the correct foreign key constraints
        Schema::table('purchase_order_items', function (Blueprint $table) {
            // Add purchase_order_id foreign key
            $table->foreign('purchase_order_id')
                  ->references('id')
                  ->on('purchase_orders')
                  ->onDelete('cascade');
                  
            // Add product_id foreign key (nullable)
            $table->foreign('product_id')
                  ->references('id')
                  ->on('products')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the foreign key constraints
        Schema::table('purchase_order_items', function (Blueprint $table) {
            $table->dropForeign(['purchase_order_id']);
            $table->dropForeign(['product_id']);
        });
    }
};
