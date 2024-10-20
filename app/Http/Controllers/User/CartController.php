<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AddOn;
use App\Models\Category;
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
        $carts = Cart::with(['product', 'addOns'])->where('user_id', Auth::id())->get();
        $totals = $this->calculateTotals();
        $appliedVoucher = session('applied_voucher');

        $categories = Category::where('status', 0)->get();
        $cartItems = Cart::where('user_id', Auth::id())->count();
        $carts = Cart::where('user_id', Auth::id())->with(['product', 'addOns'])->get();

        $subtotal = $carts->sum(function ($cart) {
            $productPrice = $cart->product->on_sale ? $cart->product->sale_price : $cart->product->price;
            return round($productPrice * $cart->quantity, 2);
        });

        $addonsTotal = $carts->sum(function ($cart) {
            return $cart->addOns->sum(function ($addOn) use ($cart) {
                return round($addOn->price * $cart->quantity, 2);
            });
        });

        $totalPrice = round($subtotal + $addonsTotal, 2);

        return view('user.cart.index', compact('carts', 'totals', 'appliedVoucher', 'categories', 'cartItems', 'totalPrice'));
    }


    public function applyVoucher(Request $request)
    {
        $request->validate([
            'voucher_code' => 'required|string',
        ]);

        if (session('applied_voucher')) {
            session()->forget('applied_voucher');
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
            'subtotal' => $totals['subtotal'],
            'addons' => $totals['addons'],
            'discount' => $totals['discount'],
            'grandTotal' => $totals['grandTotal'],
        ]);
    }

    public function removeVoucher()
    {
        session()->forget('applied_voucher');
        $totals = $this->calculateTotals();

        return response()->json([
            'success' => true,
            'message' => 'Voucher removed successfully.',
            'subtotal' => $totals['subtotal'],
            'addons' => $totals['addons'],
            'discount' => $totals['discount'],
            'grandTotal' => $totals['grandTotal'],
        ]);
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'addons' => 'nullable|array',
            'addons.*' => 'exists:add_ons,id',
        ]);

        $product = Product::findOrFail($request->product_id);
        $price = $product->on_sale ? $product->sale_price : $product->price;
        $addons = $request->input('addons', []);
        $addonsPrice = AddOn::whereIn('id', $addons)->sum('price');
        $totalPrice = $price + $addonsPrice;

        if ($product->stock > 0) {
            $cartItem = Cart::where('user_id', Auth::id())
                ->where('product_id', $product->id)
                ->first();

            if ($cartItem) {
                $cartItem->increment('quantity');
                $cartItem->update(['total_price' => $cartItem->total_price + $totalPrice]);
                $cartId = $cartItem->id;
            } else {
                $cartId = DB::table('carts')->insertGetId([
                    'user_id' => Auth::id(),
                    'product_id' => $product->id,
                    'quantity' => 1,
                    'price' => $price,
                    'total_price' => $totalPrice,
                    'image' => $product->image,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (!empty($addons)) {
                foreach ($addons as $addonId) {
                    $addonPrice = AddOn::find($addonId)->price;
                    DB::table('cart_add_on')->insert([
                        'cart_id' => $cartId,
                        'add_on_id' => $addonId,
                        'price' => $addonPrice,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            return redirect()->route('user.products.index')->with('success', 'Product added to cart.');
        } else {
            return redirect()->back()->with('failed', 'Product is out of stock.');
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
            session()->flash('error', 'You have exceeded the available stock for this product.');

            return response()->json(['success' => false, 'message' => 'Not enough stock available.']);
        }
    }


    public function getTotals()
    {
        $totals = $this->calculateTotals();
        $appliedVoucher = session('applied_voucher');

        return response()->json(array_merge($totals, ['appliedVoucher' => $appliedVoucher]));
    }

    private function calculateTotals()
    {
        $subtotal = Cart::where('user_id', Auth::id())->sum(DB::raw('price * quantity'));
        $addonsTotal = Cart::where('user_id', Auth::id())
            ->with('addOns')
            ->get()
            ->sum(function ($cartItem) {
                return $cartItem->addOns->sum('price') * $cartItem->quantity;
            });

        $voucher = session('applied_voucher');
        $discount = 0;

        if ($voucher) {
            if ($voucher->type === 'percentage') {
                $discount = ($subtotal + $addonsTotal) * ($voucher->discount / 100);
            } else {
                $discount = $voucher->discount;
            }

            if ($voucher->max_discount !== null) {
                $discount = min($discount, $voucher->max_discount);
            }
        }

        $grandTotal = max($subtotal + $addonsTotal - $discount, 0);

        return [
            'subtotal' => $subtotal,
            'addons' => $addonsTotal,
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

        $cart->delete();

        return response()->json(['success' => true, 'message' => 'Item removed.']);
    }

    public function checkout()
    {
        $carts = Cart::where('user_id', Auth::id())->with(['product', 'addOns'])->get();

        if ($carts->isEmpty()) {
            return redirect()->route('user.cart.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = $carts->sum(function ($cart) {
            $productPrice = $cart->product->on_sale ? $cart->product->sale_price : $cart->product->price;
            return round($productPrice * $cart->quantity, 2);
        });

        $addonsTotal = $carts->sum(function ($cart) {
            return $cart->addOns->sum(function ($addOn) use ($cart) {
                return round($addOn->price * $cart->quantity, 2);
            });
        });

        $totalBeforeDiscount = round($subtotal + $addonsTotal, 2);
        $appliedVoucher = session('applied_voucher');

        $discount = 0;
        if ($appliedVoucher) {
            $voucher = Voucher::where('id', $appliedVoucher->getKey())->first();
            if ($voucher && $voucher->is_active) {
                $discount = $this->calculateDiscount($totalBeforeDiscount, $voucher);
                $discount = round($discount, 2);
            } else {
                session()->forget('applied_voucher');
                $appliedVoucher = null;
            }
        }

        $grandTotal = round(max($totalBeforeDiscount - $discount, 0), 2);

        $totals = [
            'subtotal' => number_format($subtotal, 2),
            'addons' => number_format($addonsTotal, 2),
            'discount' => number_format($discount, 2),
            'grandTotal' => number_format($grandTotal, 2),
        ];

        $user = Auth::user();

        if (empty($user->address) || empty($user->phone_number)) {
            session()->flash('warning', 'Please update your address and phone number in your profile.');
        }

        $categories = Category::where('status', 0)->get();
        $cartItems = Cart::where('user_id', Auth::id())->count();

        return view('user.cart.checkout', compact('carts', 'totals', 'user', 'appliedVoucher', 'totalBeforeDiscount', 'categories', 'cartItems'));
    }

    private function calculateDiscount($total, $voucher)
    {
        if ($voucher->discount_type == 'percentage') {
            return $total * ($voucher->discount / 100);
        } else {
            return min($voucher->discount, $total);
        }
    }
}
