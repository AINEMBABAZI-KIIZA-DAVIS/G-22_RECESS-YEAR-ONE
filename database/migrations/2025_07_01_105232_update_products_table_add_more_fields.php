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
        // First, check if the columns exist before adding them
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'cost_price')) {
                $table->decimal('cost_price', 10, 2)->nullable()->after('price');
            }
            
            if (!Schema::hasColumn('products', 'sale_price')) {
                $table->decimal('sale_price', 10, 2)->nullable()->after('cost_price');
            }
            
            if (!Schema::hasColumn('products', 'reorder_quantity')) {
                $table->decimal('reorder_quantity', 10, 2)->default(0)->after('low_stock_threshold');
            }
            
            // Rename imageUrl to image_url if it exists
            if (Schema::hasColumn('products', 'imageUrl') && !Schema::hasColumn('products', 'image_url')) {
                $table->renameColumn('imageUrl', 'image_url');
            } elseif (!Schema::hasColumn('products', 'image_url')) {
                $table->string('image_url')->nullable()->after('low_stock_threshold');
            }
            
            if (!Schema::hasColumn('products', 'barcode')) {
                $table->string('barcode')->nullable()->after('image_url');
            }
            
            if (!Schema::hasColumn('products', 'weight')) {
                $table->decimal('weight', 10, 2)->nullable()->after('barcode');
            }
            
            if (!Schema::hasColumn('products', 'weight_unit')) {
                $table->string('weight_unit', 10)->nullable()->after('weight');
            }
            
            if (!Schema::hasColumn('products', 'dimensions')) {
                $table->string('dimensions')->nullable()->after('weight_unit');
            }
            
            if (!Schema::hasColumn('products', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('dimensions');
            }
            
            if (!Schema::hasColumn('products', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('is_active');
            }
            
            if (!Schema::hasColumn('products', 'tax_rate')) {
                $table->decimal('tax_rate', 5, 2)->default(0)->after('is_featured');
            }
            
            if (!Schema::hasColumn('products', 'supplier_id')) {
                $table->foreignId('supplier_id')->nullable()->after('tax_rate')->constrained('vendors')->nullOnDelete();
            }
            
            // Change quantity_in_stock and low_stock_threshold to decimal if they're not already
            $table->decimal('quantity_in_stock', 10, 2)->default(0)->change();
            $table->decimal('low_stock_threshold', 10, 2)->default(5)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop foreign key first
            if (Schema::hasColumn('products', 'supplier_id')) {
                $table->dropForeign(['supplier_id']);
            }
            
            // Drop added columns if they exist
            $columnsToDrop = [
                'cost_price',
                'sale_price',
                'reorder_quantity',
                'barcode',
                'weight',
                'weight_unit',
                'dimensions',
                'is_active',
                'is_featured',
                'tax_rate',
                'supplier_id'
            ];
            
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('products', $column)) {
                    $table->dropColumn($column);
                }
            }
            
            // Revert column name changes if needed
            if (Schema::hasColumn('products', 'image_url') && !Schema::hasColumn('products', 'imageUrl')) {
                $table->renameColumn('image_url', 'imageUrl');
            }
            
            // Revert column types if needed (you might need to adjust this based on your needs)
            $table->integer('quantity_in_stock')->default(0)->change();
            $table->integer('low_stock_threshold')->default(5)->change();
        });
    }
};
