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
        Schema::create('supply_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Supplier who made the request
            $table->string('product_name'); // Name or description of the product requested
            $table->integer('quantity');
            $table->text('notes')->nullable();
            $table->string('status')->default('pending'); // e.g., pending, confirmed_by_manufacturer, rejected, fulfilled
            $table->timestamp('confirmed_at')->nullable(); // When the manufacturer confirmed
            $table->timestamp('fulfilled_at')->nullable(); // When the request was fulfilled
            $table->text('manufacturer_notes')->nullable(); // Notes from the manufacturer
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supply_requests');
    }
};
