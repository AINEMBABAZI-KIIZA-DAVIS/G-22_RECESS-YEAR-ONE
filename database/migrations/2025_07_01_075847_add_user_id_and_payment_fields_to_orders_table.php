<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained();
            $table->enum('payment_status', ['pending', 'paid'])->default('pending');
            $table->enum('payment_method', ['mobile_money', 'bank_transfer', 'cash_on_delivery'])->nullable();
            $table->string('payment_reference')->nullable();
            $table->decimal('total_amount', 10, 2);
            $table->string('contact_number')->nullable();
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn([
                'user_id',
                'payment_status',
                'payment_method',
                'payment_reference',
                'total_amount',
                'contact_number'
            ]);
        });
    }
};
