<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AdminDashBoardController extends Controller
{
    public function showDashboard()
    {
        // Query from MySQL
        $inventory = DB::table('inventory_levels')->get();

        $inventoryLabels = $inventory->pluck('month'); // e.g. ['Jan', 'Feb', etc.]
        $inventoryValues = $inventory->pluck('level'); // e.g. [100, 200, etc.]

        $products = DB::table('top_products')->get();

        $productLabels = $products->pluck('product_name'); // ['Bread', 'Cake', etc.]
        $productValues = $products->pluck('units_sold'); // [320, 210, etc.]

        return view('admin.dashboard', [
            'inventoryLabels' => $inventoryLabels,
            'inventoryValues' => $inventoryValues,
            'productLabels' => $productLabels,
            'productValues' => $productValues,
        ]);
    }
}

