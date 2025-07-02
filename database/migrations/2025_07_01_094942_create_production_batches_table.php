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
        Schema::create('production_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('batch_number')->unique();
            $table->date('production_date');
            $table->date('expiry_date')->nullable();
            $table->integer('quantity');
            $table->integer('good_quantity')->default(0);
            $table->integer('waste_quantity')->default(0);
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled'])->default('scheduled');
            $table->text('notes')->nullable();
            $table->foreignId('produced_by')->constrained('users')->onDelete('set null');
            $table->decimal('production_cost', 10, 2)->default(0);
            $table->decimal('labor_cost', 10, 2)->default(0);
            $table->decimal('material_cost', 10, 2)->default(0);
            $table->decimal('other_costs', 10, 2)->default(0);
            $table->decimal('total_cost', 10, 2)->storedAs('production_cost + labor_cost + material_cost + other_costs');
            $table->decimal('cost_per_unit', 10, 2)->storedAs('CASE WHEN quantity > 0 THEN total_cost / quantity ELSE 0 END');
            $table->boolean('quality_check_passed')->nullable();
            $table->text('quality_notes')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_batches');
    }
};
