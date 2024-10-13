<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index()
    {
        // Fetch orders along with order items, products, and the voucher if applied
        $orders = Order::with(['orderItems.product', 'voucher'])
            ->where('user_id', Auth::id())
            ->get();

        return view('user.order.index', compact('orders'));
    }

    public function payOrder(Request $request)
    {
        $order = Order::find($request->order_id);

        if ($order && $order->payment_status == 'unpaid') {
            // Update payment status to paid and save reference number
            $order->payment_status = 'paid';
            $order->reference_number = $request->reference_number;
            // Update the delivery option if provided
            if ($request->has('delivery_option')) {
                $order->delivery_option = $request->delivery_option;
            }

            $order->save();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }


    public function placeOrder(Request $request)
    {
        $request->validate([
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'payment_method' => 'required|string',
            'grand_total' => 'required|numeric|min:0',
        ]);

        $carts = Cart::where('user_id', Auth::id())->get();

        if ($carts->isEmpty()) {
            return redirect()->route('user.cart.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = $carts->sum('total_price');
        $grandTotal = $request->grand_total;
        $discount = $subtotal - $grandTotal;

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

            // Check if a voucher was applied
            $appliedVoucher = session('applied_voucher');
            if ($appliedVoucher) {
                $voucher = Voucher::find($appliedVoucher->id);
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

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cart->product_id,
                    'quantity' => $cart->quantity,
                    'price' => $cart->product->price,
                ]);
            }

            Cart::where('user_id', Auth::id())->delete();

            // Clear the applied voucher from the session
            session()->forget('applied_voucher');

            DB::commit();

            return redirect()->route('user.order.index')->with('success', 'Order placed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('user.cart.index')->with('error', $e->getMessage());
        }
    }
}
