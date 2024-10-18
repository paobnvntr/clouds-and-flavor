<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderAddOn;
use App\Models\OrderItem;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function index()
    {
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

        $orders = Order::with(['orderItems.product', 'orderAddOns.addOn', 'voucher'])
            ->where('user_id', Auth::id())
            ->get();

        return view('user.order.index', compact('orders', 'categories', 'cartItems', 'totalPrice'));
    }

    public function payOrder(Request $request)
    {
        $order = Order::find($request->order_id);

        if ($order && $order->payment_status == 'unpaid') {
            $order->payment_status = 'paid';
            $order->reference_number = $request->reference_number;

            if ($request->has('delivery_option')) {
                $order->delivery_option = $request->delivery_option;
            }

            $order->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    private function calculateDiscount($total, $voucher)
    {
        if ($voucher->discount_type == 'percentage') {
            return $total * ($voucher->discount / 100);
        } else {
            return min($voucher->discount, $total);
        }
    }

    public function placeOrder(Request $request)
    {
        $request->merge([
            'grand_total' => str_replace(',', '', $request->input('grand_total'))
        ]);

        $validator = Validator::make($request->all(), [
            '_token' => 'required',
            'name' => 'required|string|max:255',
            'phone_number' => 'required|regex:/^09[0-9]{9}$/i',
            'address' => 'required|string|max:255',
            'grand_total' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('user.cart.index')->with('failed', 'Invalid input.');
        }

        $carts = Cart::where('user_id', Auth::id())->with(['product', 'addOns'])->get();

        if ($carts->isEmpty()) {
            return redirect()->route('user.cart.index')->with('failed', 'Your cart is empty.');
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
            $voucher = Voucher::find($appliedVoucher->getKey());
            if ($voucher && $voucher->is_active) {
                $discount = $this->calculateDiscount($totalBeforeDiscount, $voucher);
                $discount = round($discount, 2);
            } else {
                session()->forget('applied_voucher');
            }
        }

        $grandTotal = round(max($totalBeforeDiscount - $discount, 0), 2);

        if (abs($grandTotal - $request->grand_total) > 0.01) {
            return redirect()->route('user.cart.index')->with('failed', 'Order total mismatch. Please try again.');
        }

        DB::beginTransaction();

        try {
            $orderData = [
                'user_id' => Auth::id(),
                'address' => $request->address,
                'phone_number' => $request->phone_number,
                'payment_method' => $request->payment_method,
                'total_price' => $grandTotal,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'status' => 'pending',
            ];

            if ($appliedVoucher) {
                $voucher = Voucher::find($appliedVoucher->getKey());
                if ($voucher) {
                    $orderData['voucher_id'] = $voucher->id;
                    $voucher->increment('times_used');
                    if ($voucher->usage_limit && $voucher->times_used >= $voucher->usage_limit) {
                        $voucher->update(['is_active' => false]);
                    }
                }
            }

            $order = Order::create($orderData);

            foreach ($carts as $cart) {
                $product = $cart->product;

                if ($product->stock < $cart->quantity) {
                    throw new \Exception('Insufficient stock for ' . $product->product_name);
                }

                $product->stock -= $cart->quantity;
                $product->save();

                $addOrder = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cart->product_id,
                    'quantity' => $cart->quantity,
                    'price' => round($product->on_sale ? $product->sale_price : $product->price, 2),
                ]);

                if (!$addOrder) {
                    throw new \Exception('Failed to add order item for ' . $product->product_name);
                }

                if ($cart->addOns && $cart->addOns->isNotEmpty()) {
                    foreach ($cart->addOns as $addOn) {
                        $addAddOn = OrderAddOn::create([
                            'order_id' => $order->id,
                            'add_on_id' => $addOn->id,
                            'price' => round($addOn->price, 2),
                            'quantity' => $cart->quantity,
                        ]);

                        if (!$addAddOn) {
                            throw new \Exception('Failed to add order add-on for ' . $addOn->add_on_name);
                        }
                    }
                }
            }

            Cart::where('user_id', Auth::id())->delete();
            session()->forget('applied_voucher');
            DB::commit();

            return redirect()->route('user.order.index')->with('success', 'Order placed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order Placement Error:', ['error' => $e->getMessage()]);
            return redirect()->route('user.cart.index')->with('failed', $e->getMessage());
        }
    }
}