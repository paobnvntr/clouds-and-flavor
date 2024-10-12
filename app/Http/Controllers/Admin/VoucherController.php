<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::all();
        return view('admin.vouchers.index', compact('vouchers'));
    }

    public function create()
    {
        return view('admin.vouchers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:vouchers',
            'discount' => 'required|numeric|min:0',
            'type' => 'required|in:percentage,fixed',
            'expiry_date' => 'required|date|after:today',
            'is_active' => 'boolean',
            'usage_limit' => 'nullable|integer|min:1',
            'minimum_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        Voucher::create($data);

        return redirect()->route('admin.vouchers.index')->with('success', 'Voucher added successfully.');
    }

    public function edit(Voucher $voucher)
    {
        return view('admin.vouchers.edit', compact('voucher'));
    }

    public function update(Request $request, Voucher $voucher)
    {
        $request->validate([
            'code' => 'required|unique:vouchers,code,' . $voucher->id,
            'discount' => 'required|numeric|min:0',
            'type' => 'required|in:percentage,fixed',
            'expiry_date' => 'required|date|after:today',
            'is_active' => 'boolean',
            'usage_limit' => 'nullable|integer|min:1',
            'minimum_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        $voucher->update($data);

        return redirect()->route('admin.vouchers.index')->with('success', 'Voucher updated successfully.');
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();
        return redirect()->route('admin.vouchers.index')->with('success', 'Voucher deleted successfully.');
    }
}
