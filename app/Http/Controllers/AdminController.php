<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\Order;
use App\Models\POSOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function index()
    {
        $totalUsers = User::where('role', 0)->count();
        $pendingOrdersCount = Order::where('status', 'pending')->count();
        $pendingPOSOrdersCount = POSOrder::where('status', 'pending')->count();
        $totalEarningsFromOrders = Order::where('status', 'completed')->sum('total_price');
        $totalEarningsFromAddOns = DB::table('orders_add_on')
            ->join('orders', 'orders_add_on.order_id', '=', 'orders.id')
            ->join('add_ons', 'orders_add_on.add_on_id', '=', 'add_ons.id') // Join to get the add-on price
            ->where('orders.status', 'completed')
            ->sum(DB::raw('add_ons.price * orders_add_on.quantity'));

        $totalEarnings = $totalEarningsFromOrders + $totalEarningsFromAddOns;
        $formattedEarnings = number_format($totalEarnings, 2);
        $totalOrders = Order::where('status', 'completed')->count();
        $totalPOSOrders = POSOrder::where('status', 'completed')->count();

        return view('admin.dashboard', compact('pendingOrdersCount', 'totalPOSOrders', 'formattedEarnings', 'pendingPOSOrdersCount', 'totalUsers', 'totalOrders'));
    }

    public function userList()
    {
        $users = User::where('role', 0)->get();

        return view('admin.user.index', compact('users'));
    }

    public function userCreate()
    {
        return view('admin.user.create');
    }

    public function userStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|regex:/^[0-9]{11}$/',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'password' => bcrypt($request->password),
            'role' => 0,
        ]);

        return redirect()->route('admin.user.index')->with('success', 'Staff created successfully!');
    }

    public function userEdit($id)
    {
        $user = User::where('id', $id)->where('role', 0)->firstOrFail();

        return view('admin.user.edit', compact('user'));
    }

    public function userUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'address' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:11',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user = User::where('id', $id)->where('role', 0)->firstOrFail();
        $data = $request->only('name', 'email', 'address', 'phone_number');

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.user.index')->with('success', 'User updated successfully!');
    }

    public function userDestroy($id)
    {
        $user = User::where('id', $id)->where('role', 0)->firstOrFail();
        $user->delete();

        return redirect()->route('admin.user.index')->with('success', 'User deleted successfully!');
    }

    public function staffList()
    {
        $staff = User::where('role', 1)->get();

        return view('admin.staff.index', compact('staff'));
    }

    public function staffCreate()
    {
        return view('admin.staff.create');
    }

    public function staffStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|regex:/^[0-9]{11}$/',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'password' => bcrypt($request->password),
            'role' => 1,
        ]);

        return redirect()->route('admin.staff.index')->with('success', 'Staff created successfully!');
    }

    public function staffEdit($id)
    {
        $staff = User::where('id', $id)->where('role', 1)->firstOrFail();

        return view('admin.staff.edit', compact('staff'));
    }

    public function staffUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'address' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:11',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $staff = User::where('id', $id)->where('role', 1)->firstOrFail();
        $staff->name = $request->name;
        $staff->email = $request->email;
        $staff->address = $request->address;
        $staff->phone_number = $request->phone_number;

        if ($request->password) {
            $staff->password = bcrypt($request->password);
        }

        $staff->save();

        return redirect()->route('admin.staff.index')->with('success', 'Staff updated successfully!');
    }

    public function staffDestroy($id)
    {
        $staff = User::where('id', $id)->where('role', 1)->firstOrFail();
        $staff->delete();

        return redirect()->route('admin.staff.index')->with('success', 'Staff deleted successfully!');
    }

    public function showTotalEarnings(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());

        $totalEarnings = Order::whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_price');

        $formattedEarnings = number_format($totalEarnings, 2); // Format with commas

        return view('admin.total_earnings', compact('formattedEarnings', 'startDate', 'endDate'));
    }

    public function contact()
    {
        $messages = ContactMessage::all();
        
        return view('admin.contact.index', compact('messages'));
    }

    public function destroy($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();

        return redirect()->route('admin.contact.index')->with('success', 'Message deleted successfully');
    }
}
