<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AddOn;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('status', 0)->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'stock' => 'required|integer',
            'status' => 'required|in:0,1',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $product = new Product();
        $product->product_name = $request->product_name;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->stock = $request->stock;
        $product->status = $request->status;
        $product->category_id = $request->category_id;

        if ($request->hasFile('image')) {
            $imagePath = 'assets/product_image/';
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path($imagePath), $imageName);
            $product->image = $imagePath . $imageName;
        } else {
            $product->image = 'assets/product_image/unknown.jpg';
        }

        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit($id)
    {
        $product = Product::with('addOns')->findOrFail($id);
        $categories = Category::where('status', 0)->get();
        $addons = AddOn::all();

        return view('admin.products.edit', compact('product', 'categories', 'addons'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'sale_price' => 'nullable|numeric',
            'on_sale' => 'boolean',
            'stock' => 'required|integer',
            'status' => 'required|in:0,1',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'addons' => 'array', 
            'addons.*' => 'exists:add_ons,id', 
        ]);

        $product->product_name = $request->product_name;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->sale_price = $request->sale_price;
        $product->on_sale = $request->on_sale ? true : false;
        $product->stock = $request->stock;
        $product->status = $request->status;
        $product->category_id = $request->category_id;

        if ($request->hasFile('image')) {
            $imagePath = 'assets/product_image/';
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path($imagePath), $imageName);
            $product->image = $imagePath . $imageName;
        }

        $product->save();

        if ($request->has('addons')) {
            $product->addOns()->sync($request->addons);
        } else {
            $product->addOns()->sync([]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }

    public function updateStock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'stock' => 'required|integer|min:0',
        ]);

        try {
            $product = Product::findOrFail($request->product_id);
            $product->stock = $request->stock;
            $product->save();
            $status = $product->stock == 0 ? 'Unavailable' : 'Available';

            return response()->json([
                'success' => 'Stock updated successfully.',
                'status' => $status,
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating stock'], 500);
        }
    }
}
