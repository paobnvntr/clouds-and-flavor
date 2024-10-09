<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Fetch all categories where status = 0
        $categories = Category::where('status', 0)->get();

        // Check if a search query exists
        $query = $request->input('search');
        $categoryId = $request->input('category_id');
        $discountedProducts = Product::where('on_sale', 1)->get();
        $latestProducts = Product::orderBy('created_at', 'desc')->take(6)->get();
        $productsQuery = Product::query();

        // Filter by category if a category is selected
        if ($categoryId) {
            $productsQuery->where('category_id', $categoryId);
        }

        // Filter by search query
        if ($query) {
            $productsQuery->where('product_name', 'LIKE', "%{$query}%");
        }

        // Fetch products where status = 0 and apply pagination
        $products = $productsQuery->where('status', 0)->paginate(6); // 6 products per page

        // Calculate cart items and total price
        $cartItems = Cart::where('user_id', Auth::id())->count();
        $carts = Cart::where('user_id', Auth::id())->get();
        $totalPrice = $carts->sum(function ($cart) {
            return $cart->product->price * $cart->quantity;
        });

        return view('user.products.index', compact('products', 'categories', 'cartItems', 'latestProducts', 'discountedProducts', 'totalPrice'));
    }


    public function productsByCategory($categoryId)
    {
        return $this->index(request()->merge(['category_id' => $categoryId]));
    }


    public function home(Request $request)
    {
        $categories = Category::where('status', 0)->get();
        $latestProducts = Product::orderBy('created_at', 'desc')->take(6)->get();

        // Get the selected category from the request
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
}
