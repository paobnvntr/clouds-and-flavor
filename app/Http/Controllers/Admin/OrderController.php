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
        $orders = Order::whereIn('status', ['completed', 'pending'])
            ->where('payment_status', 'paid')
            ->get();

        return view('admin.orders.index', compact('orders'));
    }

    public function pendingOrder()
    {
        $orders = Order::where('status', 'pending')
            ->where('payment_status', 'paid')
            ->with('user', 'orderItems.product', 'voucher')
            ->get();

        return view('admin.orders.pending', compact('orders'));
    }

    public function completedOrder()
    {
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
        $orders = POSOrder::where('status', 'pending')->with('orderItems.product')->get();

        return view('admin.orders.pos.pending', compact('orders'));
    }

    public function posCompletedOrder()
    {
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

        return redirect()->back()->with('failed', 'Order cannot be completed.');
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

        return redirect()->back()->with('failed', 'Order cannot be completed.');
    }
}
