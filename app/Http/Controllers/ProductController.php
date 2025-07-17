<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');

        $products = Product::with('category')
            ->when($q, fn ($query) => $query->where('name', 'like', "%{$q}%"))
            ->latest()
            ->paginate(10);

        return view('dashboard.products.index', compact('products', 'q'));
    }

    public function create()
    {
        $categories = ProductCategory::all();
        return view('dashboard.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'sku'         => 'required|string|max:100|unique:products',
            'price'       => 'required|numeric',
            'stock'       => 'required|integer',
            'weight'      => 'nullable|numeric',
            'category_id' => 'required|exists:product_categories,id',
            'image_url'   => 'nullable|url',
            'description' => 'nullable|string',
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')->with('successMessage', 'Product created successfully');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = ProductCategory::all();

        return view('dashboard.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'sku'         => 'required|string|max:100|unique:products,sku,' . $product->id,
            'price'       => 'required|numeric',
            'stock'       => 'required|integer',
            'weight'      => 'nullable|numeric',
            'category_id' => 'required|exists:product_categories,id',
            'image_url'   => 'nullable|url',
            'description' => 'nullable|string',
        ]);

        $product->update($request->all());

        return redirect()->route('products.index')->with('successMessage', 'Product updated successfully');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('successMessage', 'Product deleted successfully');
    }

    public function sync($id, Request $request)
    {
        $product = Product::with('category')->findOrFail($id);

        $response = Http::post('https://api.phb-umkm.my.id/api/product/sync', [
            'client_id'           => env('CLIENT_ID'),
            'client_secret'       => env('CLIENT_SECRET'),
            'seller_product_id'   => (string) $product->id,
            'name'                => $product->name,
            'description'         => $product->description,
            'price'               => $product->price,
            'stock'               => $product->stock,
            'sku'                 => $product->sku,
            'image_url'           => $product->image_url,
            'weight'              => $product->weight,
            'is_active'           => $request->is_active == 1 ? false : true,
            'category_id'         => (string) optional($product->category)->hub_category_id,
        ]);

        if ($response->successful() && isset($response['product_id'])) {
            $product->hub_product_id = $request->is_active == 1 ? null : $response['product_id'];
            $product->save();
        }

        session()->flash('successMessage', 'Product Synced Successfully');
        return redirect()->back();
    }
}
