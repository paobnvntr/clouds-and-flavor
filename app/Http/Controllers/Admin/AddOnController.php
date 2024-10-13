<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AddOn;
use Illuminate\Http\Request;

class AddOnController extends Controller
{
    // Display a listing of the add-ons
    public function index()
    {
        $addOns = AddOn::all();
        return view('admin.addons.index', compact('addOns'));
    }

    // Show the form for creating a new add-on
    public function create()
    {
        return view('admin.addons.create');
    }

    // Store a newly created add-on in storage
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
        ]);

        AddOn::create($request->all());
        return redirect()->route('addons.index')->with('success', 'Add-on created successfully.');
    }

    // Show the form for editing the specified add-on
    public function edit($id)
    {
        $addOn = Addon::findOrFail($id);
        return view('admin.addons.edit', compact('addOn'));
    }

    // Update the specified add-on in storage
    public function update(Request $request, $id)
    {
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
        ]);

        // Find the add-on by its ID
        $addOn = AddOn::findOrFail($id);

        // Update the add-on with the validated data
        $addOn->update($request->all());

        return redirect()->route('addons.index')->with('success', 'Add-on updated successfully.');
    }

    // Remove the specified add-on from storage
    public function destroy($id)
    {
        // Find the add-on by its ID
        $addOn = AddOn::findOrFail($id);

        // Delete the add-on
        $addOn->delete();

        return redirect()->route('addons.index')->with('success', 'Add-on deleted successfully.');
    }
}
