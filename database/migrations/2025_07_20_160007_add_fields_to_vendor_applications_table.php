<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Create the requirements table
        Schema::create('vendor_applications_requirements', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('category')->nullable();
            $table->string('type')->nullable();
            $table->float('weight')->nullable();
            $table->boolean('required')->default(false);
            $table->string('status')->nullable();
            $table->timestamps();
        });

        // Pivot table for linking requirements to applications
        Schema::create('vendor_application_requirement_application', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('requirement_id');
            $table->unsignedBigInteger('application_id');
            $table->timestamps();

            $table->foreign('requirement_id')->references('id')->on('vendor_applications_requirements')->onDelete('cascade');
            $table->foreign('application_id')->references('id')->on('vendor_applications')->onDelete('cascade');
            $table->unique(['requirement_id', 'application_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_application_requirement_application');
        Schema::dropIfExists('vendor_applications_requirements');
    }
};
