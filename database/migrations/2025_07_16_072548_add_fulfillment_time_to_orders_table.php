<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('fulfillment_time')->nullable()->after('deleted_at')
                  ->comment('Time in hours between order creation and fulfillment');
        });

        // Calculate fulfillment time for existing orders
        DB::statement('
            UPDATE orders 
            SET fulfillment_time = TIMESTAMPDIFF(HOUR, created_at, updated_at) 
            WHERE status = "fulfilled" AND deleted_at IS NULL
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('fulfillment_time');
        });
    }
};