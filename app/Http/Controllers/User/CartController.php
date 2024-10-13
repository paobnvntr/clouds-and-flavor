<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::with('product')->where('user_id', Auth::id())->get();
        $totals = $this->calculateTotals();
        $appliedVoucher = session('applied_voucher');

        return view('user.cart.index', compact('carts', 'totals', 'appliedVoucher'));
    }

    public function applyVoucher(Request $request)
    {
        $request->validate([
            'voucher_code' => 'required|string',
        ]);

        if (session('applied_voucher')) {
            return response()->json([
                'success' => false,
                'message' => 'A voucher is already applied. Please remove it first.',
            ]);
        }

        $voucher = Voucher::where('code', $request->voucher_code)
            ->where('expiry_date', '>=', now())
            ->where('is_active', true)
            ->first();

        if (!$voucher) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid, inactive, or expired voucher code.',
            ]);
        }

        $totals = $this->calculateTotals();

        if ($voucher->minimum_purchase > $totals['subtotal']) {
            return response()->json([
                'success' => false,
                'message' => "This voucher requires a minimum purchase of ₱{$voucher->minimum_purchase}.",
            ]);
        }

        if ($voucher->usage_limit !== null && $voucher->times_used >= $voucher->usage_limit) {
            return response()->json([
                'success' => false,
                'message' => 'This voucher has reached its usage limit.',
            ]);
        }

        session(['applied_voucher' => $voucher]);

        $totals = $this->calculateTotals();

        return response()->json([
            'success' => true,
            'message' => 'Voucher applied successfully.',
            'voucherCode' => $voucher->code,
            'subtotal' => number_format($totals['subtotal'], 2),
            'discount' => number_format($totals['discount'], 2),
            'grandTotal' => number_format($totals['grandTotal'], 2),
        ]);
    }

    public function removeVoucher()
    {
        session()->forget('applied_voucher');
        $totals = $this->calculateTotals();

        return response()->json([
            'success' => true,
            'message' => 'Voucher removed successfully.',
            'subtotal' => number_format($totals['subtotal'], 2),
            'discount' => number_format($totals['discount'], 2),
            'grandTotal' => number_format($totals['grandTotal'], 2),
        ]);
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $product = Product::findOrFail($request->product_id);
        $price = $product->on_sale ? $product->sale_price : $product->price;

        if ($product->stock > 0) {
            $cartItem = Cart::where('user_id', Auth::id())
                ->where('product_id', $product->id)
                ->first();

            if ($cartItem) {
                $cartItem->increment('quantity');
            } else {
                DB::table('carts')->insert([
                    'user_id' => Auth::id(),
                    'product_id' => $product->id,
                    'quantity' => 1,
                    'price' => $price,
                    'total_price' => $price,
                    'image' => $product->image,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            session()->flash('message', 'Product added to cart successfully.');

            return redirect()->back();
        } else {
            return redirect()->back()->with('error', 'Product is out of stock.');
        }
    }

    public function getCartCount()
    {
        $cartCount = Session::get('cart_count', 0);
        return response()->json(['count' => $cartCount]);
    }

    public function updateQuantity(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:carts,id,user_id,' . Auth::id(),
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Cart::where('user_id', Auth::id())->where('id', $request->product_id)->first();

        if (!$cart) {
            return response()->json(['success' => false, 'message' => 'Cart item not found.'], 404);
        }

        $newQuantity = $request->quantity;
        $product = Product::findOrFail($cart->product_id);

        if ($product->stock >= $newQuantity) {
            $cart->quantity = $newQuantity;
            $cart->total_price = $product->on_sale ? $product->sale_price * $newQuantity : $product->price * $newQuantity;
            $cart->save();

            $totals = $this->calculateTotals();

            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully.',
                'newQuantity' => $cart->quantity,
                'newTotalPrice' => number_format($cart->total_price, 2),
                'subtotal' => number_format($totals['subtotal'], 2),
                'discount' => number_format($totals['discount'], 2),
                'grandTotal' => number_format($totals['grandTotal'], 2),
            ]);
        } else {
            return response()->json(['success' => false, 'message' => 'Not enough stock available.']);
        }
    }

    public function getTotals()
    {
        $totals = $this->calculateTotals();
        return response()->json($totals);
    }

    private function calculateTotals()
    {
        $subtotal = Cart::where('user_id', Auth::id())->sum('total_price');
        $voucher = session('applied_voucher');
        $discount = 0;

        if ($voucher) {
            if ($voucher->type === 'percentage') {
                $discount = $subtotal * ($voucher->discount / 100);
            } else {
                $discount = $voucher->discount;
            }

            if ($voucher->max_discount !== null) {
                $discount = min($discount, $voucher->max_discount);
            }
        }

        $grandTotal = max($subtotal - $discount, 0);

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'grandTotal' => $grandTotal,
        ];
    }

    public function removeItem(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:carts,id,user_id,' . Auth::id(),
        ]);

        $cart = Cart::where('user_id', Auth::id())->where('id', $request->product_id)->first();

        if (!$cart) {
            return response()->json(['success' => false, 'message' => 'Cart item not found.'], 404);
        }

        $product = Product::findOrFail($cart->product_id);
        $product->increment('stock', $cart->quantity);
        $cart->delete();

        return response()->json(['success' => true, 'message' => 'Item removed and stock updated.']);
    }

    public function checkout()
    {
        $carts = Cart::where('user_id', Auth::id())->get();

        if ($carts->isEmpty()) {
            return redirect()->route('user.cart.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = $carts->sum('total_price');
        $appliedVoucher = session('applied_voucher');
        $discount = $appliedVoucher ? $appliedVoucher['discount'] : 0;
        $grandTotal = $subtotal - $discount;
        $user = Auth::user();

        if (empty($user->address) || empty($user->phone_number)) {
            session()->flash('warning', 'Please update your address and phone number in your profile.');
        }

        $totals = [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'grandTotal' => $grandTotal,
        ];

        return view('user.cart.checkout', compact('carts', 'totals', 'user', 'appliedVoucher'));
    }
}
