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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique();
            $table->foreignId('vendor_id')->constrained()->onDelete('restrict');
            $table->date('order_date');
            $table->date('expected_delivery_date')->nullable();
            $table->date('delivery_date')->nullable();
            $table->string('status')->default('draft'); // draft, sent, confirmed, received, cancelled
            $table->text('notes')->nullable();
            $table->text('terms_and_conditions')->nullable();
            
            // Shipping information
            $table->string('shipping_method')->nullable();
            $table->string('shipping_terms')->nullable();
            $table->string('shipping_address')->nullable();
            
            // Financial information
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('shipping_cost', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->string('currency', 3)->default('USD');
            
            // Approval information
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->text('approval_notes')->nullable();
            
            // Received information
            $table->foreignId('received_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('received_at')->nullable();
            $table->text('receiving_notes')->nullable();
            
            // Created/Updated by
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('po_number');
            $table->index('order_date');
            $table->index('status');
            $table->index('vendor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
