<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AddOn;
use Illuminate\Http\Request;

class AddOnController extends Controller
{
    public function index()
    {
        $addOns = AddOn::all();

        return view('admin.addons.index', compact('addOns'));
    }

    public function create()
    {
        return view('admin.addons.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
        ]);

        AddOn::create($request->all());

        return redirect()->route('addons.index')->with('success', 'Add-on created successfully.');
    }

    public function edit($id)
    {
        $addOn = Addon::findOrFail($id);

        return view('admin.addons.edit', compact('addOn'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
        ]);

        $addOn = AddOn::findOrFail($id);
        $addOn->update($request->all());

        return redirect()->route('addons.index')->with('success', 'Add-on updated successfully.');
    }

    public function destroy($id)
    {
        $addOn = AddOn::findOrFail($id);
        $addOn->delete();

        return redirect()->route('addons.index')->with('success', 'Add-on deleted successfully.');
    }
}
