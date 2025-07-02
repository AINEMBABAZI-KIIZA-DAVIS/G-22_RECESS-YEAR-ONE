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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('set null');
            $table->string('batch_number')->nullable();
            $table->decimal('quantity', 12, 3)->default(0);
            $table->decimal('reserved_quantity', 12, 3)->default(0);
            $table->decimal('available_quantity', 12, 3)->storedAs('quantity - reserved_quantity');
            $table->decimal('unit_cost', 12, 4)->default(0);
            $table->decimal('total_cost', 12, 4)->storedAs('quantity * unit_cost');
            $table->date('manufacture_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('location')->nullable();
            $table->string('shelf')->nullable();
            $table->string('bin')->nullable();
            $table->string('lot_number')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->decimal('reorder_level', 12, 3)->default(0);
            $table->decimal('reorder_quantity', 12, 3)->default(0);
            $table->decimal('minimum_quantity', 12, 3)->default(0);
            $table->decimal('maximum_quantity', 12, 3)->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('last_updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['reference_type', 'reference_id']);
            $table->index('batch_number');
            $table->index('expiry_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
