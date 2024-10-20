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

        if ($selectedCategory) {
            $newProducts = Product::where('category', $selectedCategory)
                ->orderBy('created_at', 'desc')
                ->take(8)
                ->get();
        } else {
            $newProducts = Product::orderBy('created_at', 'desc')->take(8)->get();
        }

        $discountedProducts = Product::where('on_sale', 1)->get();
        $query = $request->input('search');
        $productsQuery = Product::query();

        if ($query) {
            $productsQuery->where('product_name', 'LIKE', "%{$query}%");
        }

        $products = $productsQuery->where('status', 0)->paginate(6);

        return view('dashboard', compact('products', 'newProducts', 'categories', 'latestProducts', 'discountedProducts', 'selectedCategory'));
    }

    public function shop(Request $request)
    {
        $categories = Category::where('status', 0)->get();
        $query = $request->input('search');
        $categoryId = $request->input('category_id');

        // Filter discounted products (on_sale = 1) and check if stock is greater than 0
        $discountedProducts = Product::where('on_sale', 1)
            ->where('stock', '>', 0)
            ->get();

        $latestProducts = Product::orderBy('created_at', 'desc')
            ->where('stock', '>', 0)
            ->take(6)
            ->get();

        $productsQuery = Product::query();

        if ($categoryId) {
            $productsQuery->where('category_id', $categoryId);
        }

        if ($query) {
            $productsQuery->where('product_name', 'LIKE', "%{$query}%");
        }

        // Filter products where stock is greater than 0 and status is 0
        $products = $productsQuery->where('status', 0)
            ->where('stock', '>', 0)
            ->paginate(6);

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

        return view('user.products.index', compact('products', 'categories', 'cartItems', 'latestProducts', 'discountedProducts', 'totalPrice'));
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
