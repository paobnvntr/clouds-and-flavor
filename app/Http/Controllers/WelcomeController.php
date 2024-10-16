<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WelcomeController extends Controller
{
    public function index(Request $request)
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
        
        $latestProducts = Product::orderBy('created_at', 'desc')->take(6)->get();

        $selectedCategory = $request->input('category');
        // If a category is selected, fetch products from that category; otherwise, fetch the latest new products
        if ($selectedCategory) {
            $newProducts = Product::where('category', $selectedCategory)
                ->orderBy('created_at', 'desc')
                ->take(8)
                ->get();
        } else {
            // If no category is selected, fetch the latest new products
            $newProducts = Product::orderBy('created_at', 'desc')->take(8)->get();
        }

        // This retrieves all discounted products
        $discountedProducts = Product::where('on_sale', 1)->get();
        $query = $request->input('search');
        $productsQuery = Product::query();

        // Search functionality
        if ($query) {
            $productsQuery->where('product_name', 'LIKE', "%{$query}%");
        }

        $products = $productsQuery->where('status', 0)->paginate(6);

        return view('dashboard', compact('products', 'newProducts', 'categories', 'latestProducts', 'discountedProducts', 'selectedCategory'));
    }


    public function shop(Request $request)
    {
        // Fetch all categories where status = 0
        $categories = Category::where('status', 0)->get();

        // Check if a search query exists
        $query = $request->input('search');

        // Check if a category is selected
        $categoryId = $request->input('category_id');

        // Fetch products on sale and latest products (optional, if needed elsewhere)
        $discountedProducts = Product::where('on_sale', 1)->get();
        $latestProducts = Product::orderBy('created_at', 'desc')->take(6)->get();

        // Initialize the product query
        $productsQuery = Product::query()->where('status', 0); // Fetch products with status 0

        // Filter by search query
        if ($query) {
            $productsQuery->where('product_name', 'LIKE', "%{$query}%");
        }

        // Filter by category ID if provided
        if ($categoryId) {
            $productsQuery->where('category_id', $categoryId);
        }

        // Fetch paginated products
        $products = $productsQuery->paginate(9); // Adjust the number of products per page

        // Pass data to the view
        return view('shop', compact('products', 'categories', 'latestProducts', 'discountedProducts'));
    }

    public function contact()
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

        return view('contact', compact('categories', 'cartItems', 'totalPrice'));
    }
}
