<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'orderItems.product'])->get();
        return view('admin.orders.index', compact('orders'));
    }

    public function pendingOrder()
    {
        // Fetch only the orders with status 'pending'
        $orders = Order::where('status', 'pending')->with('user', 'orderItems.product')->get();

        return view('admin.orders.pending', compact('orders'));
    }

    public function completedOrder()
    {
        // Fetch only the orders with status 'completed'
        $orders = Order::where('status', 'completed')->with('user', 'orderItems.product')->get();

        return view('admin.orders.completed', compact('orders'));
    }
}
