<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Update existing products with a default stock value
        DB::table('products')->update([
            'stock' => DB::raw('COALESCE(quantity_in_stock, 0)')
        ]);

        // Remove the old quantity_in_stock column
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'quantity_in_stock')) {
                $table->dropColumn('quantity_in_stock');
            }
        });
    }

    public function down()
    {
        // Add back the old quantity_in_stock column
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'quantity_in_stock')) {
                $table->integer('quantity_in_stock')->default(0);
            }
        });

        // Update existing products
        DB::table('products')->update([
            'quantity_in_stock' => DB::raw('COALESCE(stock, 0)')
        ]);
    }
};
