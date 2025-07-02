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
        // First create the table without foreign key constraints
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            
            // Use unsignedBigInteger instead of foreignId for more control
            $table->unsignedBigInteger('purchase_order_id');
            $table->unsignedBigInteger('product_id')->nullable();
            
            // Item details
            $table->string('product_name');
            $table->text('description')->nullable();
            $table->string('product_sku')->nullable();
            $table->string('uom')->default('pcs'); // Unit of measure
            
            // Pricing
            $table->decimal('quantity', 12, 4);
            $table->decimal('quantity_received', 12, 4)->default(0);
            $table->decimal('unit_price', 12, 4);
            $table->decimal('tax_rate', 8, 4)->default(0);
            $table->decimal('tax_amount', 12, 4)->default(0);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('discount_amount', 12, 4)->default(0);
            $table->decimal('total_amount', 12, 4);
            
            // Received information
            $table->boolean('is_received')->default(false);
            $table->timestamp('received_at')->nullable();
            $table->text('notes')->nullable();
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
        });
        
        // Add indexes after table creation
        Schema::table('purchase_order_items', function (Blueprint $table) {
            $table->index('purchase_order_id');
            $table->index('product_id');
            $table->index('is_received');
        });
        
        // Add foreign key constraints in a separate migration to ensure tables exist
        // This will be done in a new migration file
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign key constraints first
        Schema::table('purchase_order_items', function (Blueprint $table) {
            $table->dropForeign(['purchase_order_id']);
            $table->dropForeign(['product_id']);
        });
        
        // Then drop the table
        Schema::dropIfExists('purchase_order_items');
    }
};
