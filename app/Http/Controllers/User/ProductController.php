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
    public function index()
    {
        // Fetch products where status = 0
        $products = Product::where('status', 0)->get();
        $totalProducts = Product::count();
        $discountedProducts = Product::where('on_sale', 1)->get();
        $latestProducts = Product::orderBy('created_at', 'desc')->take(6)->get();
        // Fetch categories where status = 0
        $categories = Category::where('status', 0)->get();
        $cartItems = Cart::where('user_id', Auth::id())->count();
        $total = Cart::where('user_id', Auth::id())->sum(DB::raw('price * quantity'));

        return view('user.products.index', compact('products', 'totalProducts', 'latestProducts', 'discountedProducts', 'cartItems', 'total', 'categories'));
    }

    public function productsByCategory($categoryId)
    {
        // Fetch products of the given category and status = 0
        $products = Product::where('category_id', $categoryId)->where('status', 0)->get();

        // Fetch all categories
        $categories = Category::where('status', 0)->get();

        return view('user.products.index', compact('products', 'categories'));
    }
    

}
