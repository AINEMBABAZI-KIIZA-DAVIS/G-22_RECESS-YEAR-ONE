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
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 10)->unique();
            $table->string('contact_person')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address_line_1');
            $table->string('address_line_2')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('postal_code');
            $table->string('country');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->decimal('total_capacity', 10, 2)->nullable(); // in square feet/meters
            $table->decimal('used_capacity', 10, 2)->default(0);
            $table->decimal('available_capacity', 10, 2)->storedAs('COALESCE(total_capacity, 0) - used_capacity');
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->json('operating_hours')->nullable();
            $table->string('timezone')->default('UTC');
            $table->foreignId('manager_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['is_active']);
            $table->index(['city', 'state', 'country']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};
