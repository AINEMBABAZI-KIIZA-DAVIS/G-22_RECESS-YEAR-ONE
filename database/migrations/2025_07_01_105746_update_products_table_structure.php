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
        // First, modify the columns if they exist
        Schema::table('products', function (Blueprint $table) {
            // Change price to decimal if it's not already
            $table->decimal('price', 10, 2)->default(0)->change();
            
            // Add new columns if they don't exist
            if (!Schema::hasColumn('products', 'cost_price')) {
                $table->decimal('cost_price', 10, 2)->nullable()->after('price');
            }
            
            if (!Schema::hasColumn('products', 'sale_price')) {
                $table->decimal('sale_price', 10, 2)->nullable()->after('cost_price');
            }
            
            // Handle image_url/imageUrl column
            if (Schema::hasColumn('products', 'imageUrl') && !Schema::hasColumn('products', 'image_url')) {
                $table->renameColumn('imageUrl', 'image_url');
            } elseif (!Schema::hasColumn('products', 'image_url')) {
                $table->string('image_url')->nullable()->after('description');
            }
            
            // Add other new columns
            $columnsToAdd = [
                'barcode' => 'string',
                'weight' => 'decimal:2',
                'weight_unit' => 'string:10',
                'dimensions' => 'string',
                'is_active' => 'boolean',
                'is_featured' => 'boolean',
                'tax_rate' => 'decimal:5,2',
                'reorder_quantity' => 'decimal:10,2',
                'supplier_id' => 'foreignId',
            ];
            
            foreach ($columnsToAdd as $column => $type) {
                if (!Schema::hasColumn('products', $column)) {
                    if (str_contains($type, ':')) {
                        list($type, $args) = explode(':', $type, 2);
                        $args = explode(',', $args);
                        
                        if ($type === 'decimal') {
                            $table->decimal($column, (int)$args[0], (int)$args[1])->nullable();
                        } elseif ($type === 'string') {
                            $table->string($column, (int)$args[0])->nullable();
                        }
                    } elseif ($type === 'foreignId') {
                        $table->foreignId('supplier_id')->nullable()->constrained('vendors')->nullOnDelete();
                    } else {
                        $table->{$type}($column)->default($type === 'boolean' ? true : null);
                    }
                }
            }
            
            // Set default values for boolean fields
            if (Schema::hasColumn('products', 'is_active')) {
                $table->boolean('is_active')->default(true)->change();
            }
            
            if (Schema::hasColumn('products', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is a destructive operation, so we'll just drop the foreign key
        // and leave the columns in place to prevent data loss
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'supplier_id')) {
                $table->dropForeign(['supplier_id']);
            }
            
            // Note: We're not dropping any columns in the down method to prevent data loss
            // If you need to completely rollback, you should create a new migration
        });
    }
};
