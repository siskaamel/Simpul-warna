<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function sync($id)
    {
        $product = Product::findOrFail($id);
        $product->hub_product_id = $product->hub_product_id ? null : rand(1000, 9999);
        $product->save();

        return back()->with('successMessage', 'Sinkronisasi produk diperbarui.');
    }
}

