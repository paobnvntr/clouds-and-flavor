<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\POSOrder;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        // Fetch orders with status 'completed' or 'pending' and payment_status 'paid'
        $orders = Order::whereIn('status', ['completed', 'pending'])
            ->where('payment_status', 'paid')
            ->get(); // Get all matching orders

        return view('admin.orders.index', compact('orders'));
    }

    public function pendingOrder()
    {
        $orders = Order::where('status', 'pending')
            ->where('payment_status', 'paid')
            ->with('user', 'orderItems.product', 'voucher') // Corrected to use 'voucher'
            ->get();

        return view('admin.orders.pending', compact('orders'));
    }

    public function completedOrder()
    {
        // Fetch only the orders with status 'completed'
        $orders = Order::where('status', 'completed')->with('user', 'orderItems.product')->get();

        return view('admin.orders.completed', compact('orders'));
    }


    public function posAllOrder()
    {
        $orders = POSOrder::with(['orderItems.product'])->get();
        return view('admin.orders.pos.index', compact('orders'));
    }

    public function posPendingOrder()
    {
        // Fetch only the orders with status 'pending'
        $orders = POSOrder::where('status', 'pending')->with('orderItems.product')->get();

        return view('admin.orders.pos.pending', compact('orders'));
    }

    public function posCompletedOrder()
    {
        // Fetch only the orders with status 'completed'
        $orders = POSOrder::where('status', 'completed')->with('orderItems.product')->get();

        return view('admin.orders.pos.completed', compact('orders'));
    }

    public function OnlinecompleteOrder($id)
    {
        $order = Order::findOrFail($id);
        if ($order->status === 'pending') {
            $order->status = 'completed';
            $order->save();

            return redirect()->back()->with('success', 'Order marked as completed successfully.');
        }

        return redirect()->back()->with('error', 'Order cannot be completed.');
    }


    public function completeOrder($id)
    {
        $order = POSOrder::findOrFail($id);
        if ($order->status === 'pending') {
            $order->status = 'completed';
            $order->amount = $order->total_price;
            $order->save();

            return redirect()->back()->with('success', 'Order marked as completed successfully.');
        }

        return redirect()->back()->with('error', 'Order cannot be completed.');
    }
}
