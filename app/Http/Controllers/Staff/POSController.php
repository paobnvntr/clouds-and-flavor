<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\POSOrder;
use App\Models\POSOrderItem;
use App\Models\Product;
use App\Models\StaffCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class POSController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->input('search');
        $products = Product::where('stock', '>', 0)
            ->when($searchTerm, function ($query) use ($searchTerm) {
                return $query->where('product_name', 'like', '%' . $searchTerm . '%');
            })
            ->get();

        $cartItems = StaffCart::with('product')->where('staff_id', Auth::id())->get();

        $cartTotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        $categories = Category::where('status', 0)->get();

        return view('staff.pos.index', compact('products', 'cartItems', 'cartTotal', 'categories', 'searchTerm'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        try {
            $product = Product::findOrFail($request->product_id);
            $price = $product->on_sale ? $product->sale_price : $product->price;

            if ($product->stock > 0) {
                $cartItem = StaffCart::where('staff_id', Auth::id())
                    ->where('product_id', $product->id)
                    ->first();

                if ($cartItem) {
                    $cartItem->increment('quantity');
                } else {
                    StaffCart::create([
                        'staff_id' => Auth::id(),
                        'product_id' => $product->id,
                        'quantity' => 1,
                        'price' => $price,
                    ]);
                }

                $cartItems = StaffCart::with('product')->where('staff_id', Auth::id())->get();
                $cartTotal = $cartItems->sum(function ($item) {
                    return $item->quantity * $item->price;
                });

                return response()->json([
                    'success' => true,
                    'message' => 'Product added to cart successfully.',
                    'cartItems' => $cartItems,
                    'cartTotal' => number_format($cartTotal, 2),
                ]);
            } else {
                return response()->json(['success' => false, 'message' => 'Product is out of stock.']);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Product is undefined for cart item ID: ' . $request->product_id], 404);
        }
    }

    public function getOrderDetails($type, $id)
    {
        try {
            if (!is_numeric($id)) {
                return response()->json(['error' => 'Invalid order ID'], 400);
            }

            if ($type === 'user') {
                $order = Order::with(['orderItems.product', 'user'])->find($id);
            } else {
                $order = POSOrder::with('items.product')->find($id);
            }

            if (!$order) {
                return response()->json(['error' => 'Order not found'], 404);
            }

            return response()->json([
                'order' => $order,
                'items' => ($type === 'user') ? $order->orderItems : $order->items
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function showCheckoutPage(Request $request)
    {
        $cartItems = StaffCart::with('product')->where('staff_id', Auth::id())->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('staff.pos.index')->with('error', 'No items in the cart.');
        } else {
            $cartTotal = $cartItems->sum(function ($item) {
                return $item->product->on_sale ? $item->product->sale_price * $item->quantity : $item->product->price * $item->quantity;
            });

            return view('staff.pos.checkout', compact('cartItems', 'cartTotal'));
        }
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        $cartItems = DB::table('staff_carts')->where('staff_id', $request->user()->id)->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'No items in the cart.'], 400);
        }

        $totalPrice = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        DB::beginTransaction();

        try {
            if ($request->payment_method == 'Cash') {
                $order = POSOrder::create([
                    'customer_name' => $request->customer_name,
                    'staff_id' => $request->user()->id,
                    'payment_method' => $request->payment_method,
                    'total_price' => $totalPrice,
                    'status' => 'completed',
                    'amount' => $totalPrice,
                ]);
            } else {
                $order = PosOrder::create([
                    'customer_name' => $request->customer_name,
                    'staff_id' => $request->user()->id,
                    'payment_method' => $request->payment_method,
                    'total_price' => $totalPrice,
                ]);
            }

            foreach ($cartItems as $item) {
                POSOrderItem::create([
                    'pos_order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ]);

                $product = Product::find($item->product_id);
                if ($product) {
                    $product->decrement('stock', $item->quantity);
                }
            }

            DB::table('staff_carts')->where('staff_id', $request->user()->id)->delete();
            DB::commit();

            session(['order_id' => $order->id]);

            return response()->json([
                'message' => 'Order placed successfully!',
                'order_id' => $order->id,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => 'Error placing order. Please try again later.'], 500);
        }
    }

    public function orderSuccess(Request $request)
    {
        $orderId = session('order_id');

        if (!$orderId) {
            return redirect()->route('staff.pos.index')->with('error', 'Order ID not found.');
        }

        $order = POSOrder::with('orderItems.product')->findOrFail($orderId);

        return view('staff.pos.order-success', compact('order'));
    }

    public function updateCartItem(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:staff_carts,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = StaffCart::find($request->id);
        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        $cartItems = StaffCart::with('product')->where('staff_id', Auth::id())->get();
        $cartTotal = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        return response()->json([
            'cartItems' => $cartItems,
            'cartTotal' => $cartTotal,
        ]);
    }

    public function removeCartItem(Request $request)
    {
        $cartItem = StaffCart::find($request->id);
        $cartItem->delete();
        $cartItems = StaffCart::with('product')->where('staff_id', Auth::id())->get();
        
        $cartTotal = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        return response()->json([
            'cartItems' => $cartItems,
            'cartTotal' => $cartTotal,
        ]);
    }
}
