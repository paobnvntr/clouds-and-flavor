<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderAddOn;
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
        // Validate the incoming request data
        $request->validate([
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'payment_method' => 'required|string',
            'grand_total' => 'required|numeric|min:0',
            'add_ons.*.cart_id' => 'sometimes|required|exists:carts,id', // Update to match cart_id
            'add_ons.*.add_on_id' => 'sometimes|required|exists:products,id', // Update to match add_on_id
            'add_ons.*.price' => 'sometimes|required|numeric|min:0',
            'add_ons.*.quantity' => 'sometimes|required|integer|min:1', // Keeping quantity if needed
        ]);

        // Log the incoming request data for debugging
        Log::info('Order Request Data:', $request->all());

        // Retrieve the user's carts
        $carts = Cart::where('user_id', Auth::id())->get();

        // Check if the cart is empty
        if ($carts->isEmpty()) {
            return redirect()->route('user.cart.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = $carts->sum('total_price'); // Calculate subtotal from carts
        $grandTotal = $request->grand_total; // Get grand total from request
        $discount = $subtotal - $grandTotal; // Calculate discount

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Prepare the order data
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

            // Create the order
            $order = Order::create($orderData);

            // Process each cart item
            foreach ($carts as $cart) {
                $product = $cart->product;

                // Check stock availability
                if ($product->stock < $cart->quantity) {
                    throw new \Exception('Insufficient stock for ' . $product->product_name);
                }

                // Decrease the stock and save the product
                $product->stock -= $cart->quantity;
                $product->save();

                // Create an order item
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cart->product_id,
                    'quantity' => $cart->quantity,
                    'price' => $cart->product->price,
                ]);

                // Save add-ons to the orders_add_on table if they exist for this cart item
                if (isset($request->add_ons) && is_array($request->add_ons)) {
                    foreach ($request->add_ons as $addOn) {
                        // Ensure the add-on is valid and associated with the cart
                        if ($addOn['cart_id'] == $cart->id) {
                            Log::info('Adding Add-On:', $addOn);
                            OrderAddOn::create([
                                'order_id' => $order->id,
                                'cart_id' => $cart->id, // Associate add-on with the cart
                                'add_on_id' => $addOn['add_on_id'], // Product ID for the add-on
                                'price' => $addOn['price'], // Price of the add-on
                            ]);
                        }
                    }
                } else {
                    Log::info('No add-ons to add.');
                }
            }

            // Clear the user's cart
            Cart::where('user_id', Auth::id())->delete();

            // Clear the applied voucher from the session
            session()->forget('applied_voucher');

            // Commit the transaction
            DB::commit();

            return redirect()->route('user.order.index')->with('success', 'Order placed successfully.');
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollBack();
            Log::error('Order Placement Error:', ['error' => $e->getMessage()]); // Log error message
            return redirect()->route('user.cart.index')->with('error', $e->getMessage());
        }
    }
}
