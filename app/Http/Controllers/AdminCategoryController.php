<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AdminCategoryController extends Controller
{
    public function index()
    {
        $categories = Categories::latest()->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    public function sync($id, Request $request)
    {
        $category = Categories::findOrFail($id);

        $response = Http::post('https://api.phb-umkm.my.id/api/product-category/sync', [
            'client_id' => env('CLIENT_ID'),
            'client_secret' => env('CLIENT_SECRET'),
            'seller_product_category_id' => (string) $category->id,
            'name' => $category->name,
            'description' => $category->description,
            'is_active' => $category->hub_category_id ? false : true,
        ]);

        if ($response->successful() && isset($response['product_category_id'])) {
            $category->hub_category_id = $category->hub_category_id ? null : $response['product_category_id'];
            $category->save();
        }

        session()->flash('successMessage', 'Kategori berhasil disinkronisasi.');
        return redirect()->back();
    }
}

