<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // First, update existing records
        DB::table('supplier_requests')
            ->where('status', 'confirmed_by_manufacturer')
            ->update(['status' => 'approved']);

        // Then modify the enum
        Schema::table('supplier_requests', function (Blueprint $table) {
            $table->enum('status', [
                'pending', 
                'approved', 
                'fulfilled', 
                'rejected'
            ])->default('pending')->change();
        });
    }

    public function down()
    {
        // Revert existing records
        DB::table('supplier_requests')
            ->where('status', 'approved')
            ->update(['status' => 'confirmed_by_manufacturer']);

        // Revert the enum
        Schema::table('supplier_requests', function (Blueprint $table) {
            $table->enum('status', [
                'pending', 
                'confirmed_by_manufacturer', 
                'fulfilled', 
                'rejected'
            ])->default('pending')->change();
        });
    }
}; 