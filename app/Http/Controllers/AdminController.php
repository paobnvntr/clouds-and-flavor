<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function index()
    {
        $totalUsers = User::where('role', 0)->count();
        $pendingOrdersCount = Order::where('status', 'pending')->count();
        $totalEarnings = Order::sum('total_price');
        $formattedEarnings = number_format($totalEarnings, 2);
        $totalOrders = Order::where('status', 'completed')->count();

        return view('admin.dashboard', compact('pendingOrdersCount', 'formattedEarnings', 'totalUsers', 'totalOrders'));
    }


    public function userList()
    {
        // Fetch users where role is 0 (for regular users)
        $users = User::where('role', 0)->get();

        // Pass the users to the view
        return view('admin.user.index', compact('users'));
    }

    public function userCreate()
    {
        // Return the view for creating a new staff member
        return view('admin.user.create');
    }

    public function userStore(Request $request)
    {
        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|regex:/^[0-9]{11}$/',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Create the new staff member
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'password' => bcrypt($request->password),
            'role' => 0,
        ]);

        // Redirect with success message
        return redirect()->route('admin.user.index')->with('success', 'Staff created successfully!');
    }

    public function userEdit($id)
    {
        // Find the user by ID where the role is 0 (regular user)
        $user = User::where('id', $id)->where('role', 0)->firstOrFail();

        // Pass the user to the view
        return view('admin.user.edit', compact('user'));
    }

    public function userUpdate(Request $request, $id)
    {
        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'address' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:11',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Find and update the user
        $user = User::where('id', $id)->where('role', 0)->firstOrFail();

        // Update user fields
        $data = $request->only('name', 'email', 'address', 'phone_number');

        // Update password if provided
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        // Redirect with success message
        return redirect()->route('admin.user.index')->with('success', 'User updated successfully!');
    }



    public function userDestroy($id)
    {
        // Find the user by ID where the role is 0
        $user = User::where('id', $id)->where('role', 0)->firstOrFail();

        // Delete the user
        $user->delete();

        // Redirect with success message
        return redirect()->route('admin.user.index')->with('success', 'User deleted successfully!');
    }





    public function staffList()
    {
        // Fetch staff where role is 1
        $staff = User::where('role', 1)->get();

        // Pass the staff to the view
        return view('admin.staff.index', compact('staff'));
    }

    public function staffCreate()
    {
        // Return the view for creating a new staff member
        return view('admin.staff.create');
    }

    public function staffStore(Request $request)
    {
        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|regex:/^[0-9]{11}$/',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Create the new staff member
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'password' => bcrypt($request->password),
            'role' => 1,
        ]);

        // Redirect with success message
        return redirect()->route('admin.staff.index')->with('success', 'Staff created successfully!');
    }

    public function staffEdit($id)
    {
        // Find the staff by ID where the role is 1
        $staff = User::where('id', $id)->where('role', 1)->firstOrFail();

        // Pass the staff to the view
        return view('admin.staff.edit', compact('staff'));
    }

    public function staffUpdate(Request $request, $id)
    {
        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'address' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:11',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        // Find the staff by ID
        $staff = User::where('id', $id)->where('role', 1)->firstOrFail();

        // Update staff details
        $staff->name = $request->name;
        $staff->email = $request->email;
        $staff->address = $request->address;
        $staff->phone_number = $request->phone_number;

        // Update password if provided
        if ($request->password) {
            $staff->password = bcrypt($request->password);
        }

        $staff->save(); // Save the updated staff details

        // Redirect with success message
        return redirect()->route('admin.staff.index')->with('success', 'Staff updated successfully!');
    }


    public function staffDestroy($id)
    {
        // Find the staff by ID where the role is 1
        $staff = User::where('id', $id)->where('role', 1)->firstOrFail();

        // Delete the staff
        $staff->delete();

        // Redirect with success message
        return redirect()->route('admin.staff.index')->with('success', 'Staff deleted successfully!');
    }

    public function showTotalEarnings(Request $request)
    {
        // Default values for date filtering
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());

        // Calculate total earnings for the specified date range
        $totalEarnings = Order::whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_price');

        // Format the earnings
        $formattedEarnings = number_format($totalEarnings, 2); // Format with commas

        return view('admin.total_earnings', compact('formattedEarnings', 'startDate', 'endDate'));
    }
}
