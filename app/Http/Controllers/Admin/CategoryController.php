<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:0,1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Set default image path
        $imagePath = 'assets/category_image/unknown.jpg'; // Default image

        // Check if an image is uploaded
        if ($request->hasFile('image')) {
            // Store image in public/assets/category_image folder
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();

            // Store the image in the public directory and return its relative path
            $image->move(public_path('assets/category_image'), $imageName);

            // Set the image path relative to public directory
            $imagePath = 'assets/category_image/' . $imageName;
        }

        // Create new category
        Category::create([
            'name' => $request->name,
            'status' => $request->status,
            'image' => $imagePath,
        ]);

        // Redirect back with success message
        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }




    public function show($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.show', compact('category'));
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Find the category
        $category = Category::findOrFail($id);
        $category->name = $request->name;
        $category->status = $request->status;

        // Handle the image upload
        if ($request->hasFile('image')) {
            // Create a unique filename for the image
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();

            // Move the image to the public/assets/category_image directory
            $request->file('image')->move(public_path('assets/category_image'), $imageName);

            // Delete the old image if it exists (except if it's the default image)
            if ($category->image && $category->image != 'assets/category_image/unknown.jpg') {
                if (file_exists(public_path($category->image))) {
                    unlink(public_path($category->image));
                }
            }

            // Save the new image path
            $category->image = 'assets/category_image/' . $imageName;
        }

        // Save the updated category
        $category->save();

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }


    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }
}
