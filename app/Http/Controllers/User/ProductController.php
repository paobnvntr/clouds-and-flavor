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
        $categories = Category::where('status', 0)->get();
        $query = $request->input('search');
        $categoryId = $request->input('category_id');
        $discountedProducts = Product::where('on_sale', 1)->get();
        $latestProducts = Product::orderBy('created_at', 'desc')->take(6)->get();
        $productsQuery = Product::query();

        if ($categoryId) {
            $productsQuery->where('category_id', $categoryId);
        }

        if ($query) {
            $productsQuery->where('product_name', 'LIKE', "%{$query}%");
        }

        $products = $productsQuery->where('status', 0)->paginate(6);
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

    public function productsByCategory($categoryId)
    {
        return $this->index(request()->merge(['category_id' => $categoryId]));
    }

    public function home(Request $request)
    {
        $categories = Category::where('status', 0)->get();
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

        return view('dashboard', compact('products', 'newProducts', 'categories', 'latestProducts', 'discountedProducts', 'selectedCategory', 'cartItems', 'totalPrice'));
    }

    public function productsDetails($id)
    {
        $product = Product::with('addOns')->findOrFail($id);
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

        return view('user.products.product-details', compact('product', 'cartItems', 'totalPrice', 'categories'));
    }
}
