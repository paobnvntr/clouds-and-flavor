<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\POSOrder;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index()
    {
        // Fetch total orders, pending orders, and completed orders for the staff
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $completedOrders = Order::where('status', 'completed')->count();

        return view('staff.dashboard', compact('totalOrders', 'pendingOrders', 'completedOrders'));
    }

    public function dashboard()
    {
        // Fetch total orders, pending orders, and completed orders for the staff
        $userOrders = Order::all();
        $posOrders = POSOrder::all();


        $OLpendingOrders = Order::where('status', 'pending')
            ->where('payment_status', 'paid')
            ->count();
        $POSpendingOrders = POSOrder::where('status', 'pending')->count();
        $completedOrders = Order::where('status', 'completed')->count();
        $completedOrdersCount = Order::where('status', 'completed')->count() + PosOrder::where('status', 'completed')->count();


        // Fetch orders with 'pending' status, 'paid' payment status, and 'to-deliver' delivery option
        $toDeliverOrders = Order::where('status', 'pending')
            ->where('payment_status', 'paid')
            ->where('delivery_option', 'to-deliver')
            ->count();

        // Fetch orders with 'pending' status, 'paid' payment status, and 'pick-up' delivery option
        $pickUpOrders = Order::where('status', 'pending')
            ->where('payment_status', 'paid')
            ->where('delivery_option', 'pick-up')
            ->count();

        $toDeliverOrdersCount = Order::where('status', 'completed')
            ->where('delivery_option', 'to-deliver')
            ->count();

        $pickUpOrdersCount = Order::where('status', 'completed')
            ->where('delivery_option', 'pick-up')
            ->count();

        return view('staff.orders.dashboard', compact('OLpendingOrders', 'completedOrdersCount', 'toDeliverOrdersCount', 'toDeliverOrders', 'pickUpOrders', 'pickUpOrdersCount', 'POSpendingOrders', 'completedOrders'));
    }


    public function pendingList()
    {
        $userOrders = Order::where('status', 'pending')->with('user')->get();
        $posOrders = PosOrder::where('status', 'pending')->with('items')->get();

        return view('staff.orders.pending-order', compact('userOrders', 'posOrders'));
    }
    public function completedList()
    {
        $userOrders = Order::where('status', 'completed')->with('user')->get();
        $posOrders = PosOrder::where('status', 'completed')->with('items')->get();

        return view('staff.orders.completed-order', compact('userOrders', 'posOrders'));
    }



    public function orderList()
    {
        $userOrders = Order::all(); // Orders from users
        $posOrders = POSOrder::all(); // Orders from POS

        return view('staff.orders.index', compact('userOrders', 'posOrders'));
    }

    public function completeOrder(Request $request)
    {
        $order = Order::find($request->order_id);

        if ($order && $order->status !== 'completed') {
            $order->status = 'completed';
            $order->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Order not found or already completed.']);
    }

    public function completePosOrder(Request $request)
    {
        $order = PosOrder::find($request->order_id); // Adjust to your model

        if ($order && $order->status !== 'completed') {
            $order->status = 'completed';
            $order->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Order not found or already completed.']);
    }


    public function onlinePending()
    {
        $orders = Order::where('status', 'pending')
            ->where('payment_status', 'paid')
            ->with('user', 'orderItems.product') // Eager loading to optimize performance
            ->get();

        return view('staff.orders.online-pending', compact('orders'));
    }

    public function posPending()
    {
        $orders = PosOrder::where('status', 'pending')->get();

        return view('staff.orders.pos-pending', compact('orders'));
    }


    public function dORp()
    {
        // Fetch user orders with status 'pending' and payment status 'paid', filtered by delivery option
        $deliveryOrders = Order::where('status', 'pending')
            ->where('payment_status', 'paid')
            ->where('delivery_option', 'to-deliver')
            ->with('user')
            ->get();

        $pickupOrders = Order::where('status', 'pending')
            ->where('payment_status', 'paid')
            ->where('delivery_option', 'pick-up')
            ->with('user')
            ->get();


        return view('staff.orders.deliver-or-pickup', compact('deliveryOrders', 'pickupOrders'));
    }

    public function dORpCompleted()
    {
        // Count completed delivery orders with status 'completed' and delivery option 'to-deliver'
        $toDeliverOrdersCount = Order::where('status', 'completed')
            ->where('delivery_option', 'to-deliver')
            ->with('user')
            ->get();

        // Count completed pickup orders with status 'completed' and delivery option 'pick-up'
        $pickUpOrdersCount = Order::where('status', 'completed')
            ->where('delivery_option', 'pick-up')
            ->with('user')
            ->get();

        // Ensure both variables are being passed to the view
        return view('staff.orders.deliver-or-pickup-completed', compact('toDeliverOrdersCount', 'pickUpOrdersCount'));
    }

    public function dORpComplete($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }

        // Update order status
        $order->status = 'completed';
        $order->save();

        return response()->json(['success' => true]);
    }
}
