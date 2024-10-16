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

        // Fetch orders along with order items, products, and the voucher if applied
        $orders = Order::with(['orderItems.product', 'orderAddOns.addOn', 'voucher'])
            ->where('user_id', Auth::id())
            ->get();

        return view('user.order.index', compact('orders', 'categories', 'cartItems', 'totalPrice'));
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

    private function calculateDiscount($total, $voucher)
    {
        if ($voucher->discount_type == 'percentage') {
            return $total * ($voucher->discount_value / 100);
        } else { // fixed amount
            return min($voucher->discount_value, $total); // Ensure discount doesn't exceed total
        }
    }

    public function placeOrder(Request $request)
    {
        $request->merge([
            'grand_total' => str_replace(',', '', $request->input('grand_total'))
        ]);

        // dd($request->all()); // This will output the modified request data

        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            '_token' => 'required',
            'name' => 'required|string|max:255',
            'phone_number' => 'required|regex:/^09[0-9]{9}$/i',
            'address' => 'required|string|max:255',
            'grand_total' => 'required|numeric|min:0', // Ensure grand_total is numeric
            'payment_method' => 'required|string',
        ]);

        if ($validator->fails()) {
            // return redirect()->route('user.cart.index')->with('error', 'Invalid input.');
            dd($validator->errors());
        }

        // Log the incoming request data for debugging
        // Log::info('Order Request Data:', $request->all());

        // Retrieve the user's carts with products and add-ons
        $carts = Cart::where('user_id', Auth::id())->with(['product', 'addOns'])->get();

        // Check if the cart is empty
        if ($carts->isEmpty()) {
            return redirect()->route('user.cart.index')->with('error', 'Your cart is empty.');
        }

        // Calculate subtotal for products
        $subtotal = $carts->sum(function ($cart) {
            // Determine the product price based on sales
            $productPrice = $cart->product->on_sale ? $cart->product->sale_price : $cart->product->price;
            return round($productPrice * $cart->quantity, 2);
        });

        // Calculate total for add-ons
        $addonsTotal = $carts->sum(function ($cart) {
            return $cart->addOns->sum(function ($addOn) use ($cart) {
                return round($addOn->price * $cart->quantity, 2);
            });
        });

        // Combine subtotal and add-ons total to get the total before discount
        $totalBeforeDiscount = round($subtotal + $addonsTotal, 2);

        // Get the applied voucher from the session
        $appliedVoucher = session('applied_voucher');

        // Calculate discount if a voucher is applied
        $discount = 0;
        if ($appliedVoucher) {
            $voucher = Voucher::find($appliedVoucher->getKey());
            if ($voucher && $voucher->is_active) {
                $discount = $this->calculateDiscount($totalBeforeDiscount, $voucher);
                $discount = round($discount, 2); // Ensure discount is rounded
            } else {
                // If the voucher is no longer valid, remove it from the session
                session()->forget('applied_voucher');
            }
        }

        // Calculate grand total
        $grandTotal = round(max($totalBeforeDiscount - $discount, 0), 2); // Ensure it doesn't fall below 0

        // Verify that the calculated grand total matches the one from the request
        if (abs($grandTotal - $request->grand_total) > 0.01) { // Allow for small floating-point discrepancies
            return redirect()->route('user.cart.index')->with('error', 'Order total mismatch. Please try again.');
        }

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
                $addOrder = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cart->product_id,
                    'quantity' => $cart->quantity,
                    'price' => round($product->on_sale ? $product->sale_price : $product->price, 2), // Ensure price is rounded
                ]);

                if (!$addOrder) {
                    throw new \Exception('Failed to add order item for ' . $product->product_name);
                }

                // Check if there are add-ons associated with the cart item
                if ($cart->addOns && $cart->addOns->isNotEmpty()) {
                    foreach ($cart->addOns as $addOn) {
                        $addAddOn = OrderAddOn::create([
                            'order_id' => $order->id,
                            'add_on_id' => $addOn->id,
                            'price' => round($addOn->price, 2), // Ensure add-on price is rounded
                            'quantity' => $cart->quantity, // Use the cart quantity for add-ons
                        ]);

                        if (!$addAddOn) {
                            throw new \Exception('Failed to add order add-on for ' . $addOn->add_on_name);
                        }
                    }
                }
            }

            // Clear the user's cart
            Cart::where('user_id', Auth::id())->delete();

            // Clear the applied voucher from the session
            session()->forget('applied_voucher');

            // Commit the transaction
            DB::commit();

            return redirect()->route('user.order.index')->with('message', 'Order placed successfully.');
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollBack();
            Log::error('Order Placement Error:', ['error' => $e->getMessage()]); // Log error message
            return redirect()->route('user.cart.index')->with('error', $e->getMessage());
        }
    }
}