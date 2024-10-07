<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['orderItems.product'])->where('user_id', Auth::id())->get();
        return view('user.order.index', compact('orders'));
    }




    public function placeOrder(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'payment_method' => 'required|string',
        ]);

        // Fetch the cart data for the current user
        $carts = Cart::where('user_id', Auth::id())->get();

        if ($carts->isEmpty()) {
            return redirect()->route('user.cart.index')->with('error', 'Your cart is empty.');
        }

        // Calculate the total price
        $totalPrice = $carts->sum(function ($cart) {
            return $cart->product->price * $cart->quantity;
        });

        // Create the order
        $order = Order::create([
            'user_id' => Auth::id(),
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'payment_method' => $request->payment_method,
            'total_price' => $totalPrice, // Make sure to include the total price here
            'status' => 'pending',
        ]);

        // Optionally, you can loop through the carts to create order items if necessary
        foreach ($carts as $cart) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cart->product_id,
                'quantity' => $cart->quantity,
                'price' => $cart->product->price,
            ]);
        }

        // Clear the cart after placing the order
        Cart::where('user_id', Auth::id())->delete();

        return redirect()->route('user.order.index')->with('success', 'Order placed successfully.');
    }
}
