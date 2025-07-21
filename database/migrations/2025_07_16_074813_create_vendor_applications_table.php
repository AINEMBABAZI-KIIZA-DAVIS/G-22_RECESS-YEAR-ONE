<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vendor_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('company_name');
            $table->string('contact_email');
            $table->string('annual_revenue_pdf');
            $table->string('regulatory_pdf');
            $table->string('reputation_pdf');
            $table->enum('status', ['pending', 'under_review', 'visit_scheduled', 'approved', 'rejected'])->default('pending');
            $table->text('validation_results')->nullable();
            $table->timestamp('scheduled_visit_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_applications');
    }
};
