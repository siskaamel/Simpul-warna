<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $productCount = Product::count();
        $categoryCount = Category::count();
        $syncedProducts = Product::whereNotNull('hub_product_id')->count();
        $syncedCategories = Category::whereNotNull('hub_category_id')->count();

        return view('admin.dashboard', compact(
            'productCount',
            'categoryCount',
            'syncedProducts',
            'syncedCategories'
        ));
    }
}

