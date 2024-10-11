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
        // Get the search term from the request
        $searchTerm = $request->input('search');

        // Fetch available products, filter by search term if provided
        $products = Product::where('stock', '>', 0)
            ->when($searchTerm, function ($query) use ($searchTerm) {
                return $query->where('product_name', 'like', '%' . $searchTerm . '%');
            })
            ->get();

        // Fetch current staff cart items with product data
        $cartItems = StaffCart::with('product')->where('staff_id', Auth::id())->get();

        // Calculate cart total
        $cartTotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        // Fetch available categories
        $categories = Category::where('status', 0)->get(); // Only fetch available categories

        // Pass products, cart items, cart total, and categories to the view
        return view('staff.pos.index', compact('products', 'cartItems', 'cartTotal', 'categories', 'searchTerm'));
    }



    public function addToCart(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        try {
            // Retrieve the product
            $product = Product::findOrFail($request->product_id);

            // Determine the price to use (sale price if on sale, otherwise regular price)
            $price = $product->on_sale ? $product->sale_price : $product->price;

            // Check if stock is available
            if ($product->stock > 0) {
                // Check if the product already exists in the cart
                $cartItem = StaffCart::where('staff_id', Auth::id())
                    ->where('product_id', $product->id)
                    ->first();

                if ($cartItem) {
                    // If the product is already in the cart, increment the quantity
                    $cartItem->increment('quantity');
                } else {
                    // Directly insert a new entry into the staff_carts table
                    StaffCart::create([
                        'staff_id' => Auth::id(),
                        'product_id' => $product->id,
                        'quantity' => 1, // Default quantity is 1
                        'price' => $price, // Use sale price if on sale
                    ]);
                }

                // Get updated cart items with eager loading
                $cartItems = StaffCart::with('product')->where('staff_id', Auth::id())->get();
                $cartTotal = $cartItems->sum(function ($item) {
                    return $item->quantity * $item->price;
                });

                return response()->json([
                    'success' => true,
                    'message' => 'Product added to cart successfully.',
                    'cartItems' => $cartItems,
                    'cartTotal' => number_format($cartTotal, 2), // Format cart total with commas
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
            // Check if the provided ID is valid
            if (!is_numeric($id)) {
                return response()->json(['error' => 'Invalid order ID'], 400);
            }

            if ($type === 'user') {
                // Retrieve user order with related items and user
                $order = Order::with(['orderItems.product', 'user'])->find($id);
            } else {
                // Retrieve POS order with related items
                $order = POSOrder::with('items.product')->find($id);
            }

            // Check if order was found
            if (!$order) {
                return response()->json(['error' => 'Order not found'], 404);
            }

            // Return the order with its items
            return response()->json([
                'order' => $order,
                'items' => ($type === 'user') ? $order->orderItems : $order->items // Ensure correct items are returned
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function showCheckoutPage()
    {
        $cartItems = StaffCart::with('product')->where('staff_id', Auth::id())->get();

        // Calculate total considering sale price
        $cartTotal = $cartItems->sum(function ($item) {
            return $item->product->on_sale ? $item->product->sale_price * $item->quantity : $item->product->price * $item->quantity;
        });

        return view('staff.pos.checkout', compact('cartItems', 'cartTotal'));
    }

    public function placeOrder(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'customer_name' => 'required|string',
            'table_number' => 'required|integer',
            'payment_method' => 'required|string',
        ]);

        // Retrieve cart items for the staff
        $cartItems = DB::table('staff_carts')->where('staff_id', $request->user()->id)->get();

        // Check if there are cart items
        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'No items in the cart.'], 400);
        }

        // Calculate total price
        $totalPrice = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Insert the order using the PosOrder model
            $order = PosOrder::create([
                'customer_name' => $request->customer_name,
                'table_number' => $request->table_number,
                'staff_id' => $request->user()->id,
                'payment_method' => $request->payment_method,
                'total_price' => $totalPrice,
            ]);

            // Insert each cart item into pos_order_items using the PosOrderItem model
            foreach ($cartItems as $item) {
                // Create a new order item
                POSOrderItem::create([
                    'pos_order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ]);

                // Decrement the stock of the product
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->decrement('stock', $item->quantity);
                }
            }

            // Clear the cart after placing the order
            DB::table('staff_carts')->where('staff_id', $request->user()->id)->delete();

            // Commit the transaction
            DB::commit();

            // Return a JSON response indicating success
            return response()->json([
                'message' => 'Order placed successfully!',
                'order_id' => $order->id,
            ]);
        } catch (\Exception $e) {
            // Rollback the transaction if there is an error
            DB::rollBack();

            return response()->json(['message' => 'Error placing order. Please try again later.'], 500);
        }
    }


    public function orderSuccess()
    {
        return view('staff.pos.order-success');
    }

    public function updateCartItem(Request $request)
    {
        // Validate the request data
        $request->validate([
            'id' => 'required|exists:staff_carts,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Find the cart item by ID
        $cartItem = StaffCart::find($request->id);

        // Update the quantity
        $cartItem->quantity = $request->quantity;
        $cartItem->save(); // Save changes to the database

        // Fetch updated cart items and total
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
        // Assuming you have a method to remove the item from the cart
        $cartItem = StaffCart::find($request->id);
        $cartItem->delete(); // Remove the cart item

        // Fetch updated cart items and total
        $cartItems = StaffCart::with('product')->where('staff_id', Auth::id())->get();
        $cartTotal = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        return response()->json([
            'cartItems' => $cartItems,
            'cartTotal' => $cartTotal,
        ]);
    }

    public function filterProducts(Request $request)
    {
        $categoryId = $request->input('category_id');

        // Fetch products based on category ID
        $products = Product::when($categoryId, function ($query) use ($categoryId) {
            return $query->where('category_id', $categoryId);
        })->get();

        return response()->json(['products' => $products]);
    }
}
