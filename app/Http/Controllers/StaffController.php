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

    public function orderList()
    {
        $userOrders = Order::all(); // Orders from users
        $posOrders = POSOrder::all(); // Orders from POS

        return view('staff.orders.index', compact('userOrders', 'posOrders'));
    }
}
