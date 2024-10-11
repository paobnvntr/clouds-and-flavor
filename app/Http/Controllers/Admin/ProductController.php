<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('status', 0)->get(); // Fetch only categories with status 0
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
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

        // Handle the image upload
        if ($request->hasFile('image')) {
            // Define the path where the image should be stored
            $imagePath = 'assets/product_image/';
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName(); // Create a unique image name
            $request->file('image')->move(public_path($imagePath), $imageName); // Move the image to the public/assets/product_image directory
            $product->image = $imagePath . $imageName; // Save the relative path to the database
        } else {
            $product->image = 'assets/product_image/unknown.jpg'; // Set to default image if no upload
        }

        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::where('status', 0)->get(); // Fetch only available categories
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
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
        ]);

        // Update product details
        $product->product_name = $request->product_name;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->sale_price = $request->sale_price;
        $product->on_sale = $request->on_sale ? true : false;
        $product->stock = $request->stock;
        $product->status = $request->status;
        $product->category_id = $request->category_id;

        // Handle the image upload
        if ($request->hasFile('image')) {
            // Define the path where the image should be stored
            $imagePath = 'assets/product_image/';
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path($imagePath), $imageName);
            $product->image = $imagePath . $imageName;
        }
        // If no new image is uploaded, retain the existing image in the database
        // No need to assign $product->image here

        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
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

            // Check if the stock is 0 to determine status
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
