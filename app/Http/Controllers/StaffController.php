<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\POSOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

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
        $posOrders = PosOrder::where('status', 'pending')->with('orderItems')->get();

        return view('staff.orders.pending-order', compact('userOrders', 'posOrders'));
    }

    public function completedList()
    {
        $orders = Order::where('status', 'completed')->with('user')->get();

        return view('staff.orders.completed-order', compact('orders'));
    }

    public function posOrderList()
    {
        $orders = POSOrder::all(); // Orders from POS

        return view('staff.orders.pos.posOrder', compact('orders'));
    }

    public function orderList()
    {
        // Fetch orders with status 'completed' or 'pending' and payment_status 'paid'
        $orders = Order::whereIn('status', ['completed', 'pending'])
            ->where('payment_status', 'paid')
            ->get(); // Get all matching orders

        return view('staff.orders.index', compact('orders'));
    }




    public function completeOrder(Request $request)
    {
        $order = Order::find($request->order_id);

        if ($order && $order->status !== 'completed') {
            // Mark the order as completed
            $order->status = 'completed';
            $order->save();

            // Get the phone number from the 'phone_number' field in the orders table
            $phoneNumber = $order->phone_number;

            if ($phoneNumber) {
                try {
                    $this->sendSmsNotification($phoneNumber, $order->id, $order->user_id);
                } catch (\Exception $e) {
                    // Handle any error from SMS sending
                    return response()->json(['success' => true, 'message' => 'Order completed, but SMS could not be sent.']);
                }
            }

            return response()->json(['success' => true, 'message' => 'Order completed and SMS sent successfully!']);
        }

        return response()->json(['success' => false, 'message' => 'Order not found or already completed.']);
    }

    protected function formatPhoneNumber($phone_number)
    {
        // Remove any non-numeric characters
        $number = preg_replace('/\D/', '', $phone_number);

        // Check if it already starts with a country code (e.g., +63 for Philippines)
        if (substr($number, 0, 2) !== '63') {
            // Assuming it's a local number, add the country code for the Philippines (+63)
            $number = '63' . ltrim($phone_number, '0'); // Remove leading 0 and add country code
        }

        return '+' . $number;
    }


    protected function sendSmsNotification($phoneNumber, $orderId, $userId)
    {
        // Get user information
        $user = User::find($userId);

        if ($user && $user->phone_number) {
            // Twilio credentials from .env
            $sid = env('TWILIO_SID');
            $token = env('TWILIO_AUTH_TOKEN');
            $twilio_number = env('TWILIO_PHONE_NUMBER');
            $client = new Client($sid, $token);

            // Ensure phone number is in E.164 format (e.g., +63947252XXXX for the Philippines)
            $formattedNumber = $this->formatPhoneNumber($user->phone_number);

            // Compose the SMS message
            $message = "Hi {$user->name}, your order has been successfully completed. Thank you for ordering!";

            try {
                // Send the SMS
                $client->messages->create(
                    $formattedNumber, // User's formatted phone number
                    [
                        'from' => $twilio_number,
                        'body' => $message
                    ]
                );
            } catch (\Exception $e) {
                // Handle exception if SMS fails
                Log::error("Failed to send SMS: " . $e->getMessage());
            }
        }
    }

    // private function sendSmsNotification($phoneNumber, $orderId)
    // {
    //     // Twilio configuration from .env
    //     $sid = env('TWILIO_SID');
    //     $token = env('TWILIO_AUTH_TOKEN');
    //     $twilioNumber = env('TWILIO_PHONE_NUMBER');

    //     $client = new Client($sid, $token);

    //     // Send the SMS
    //     $message = "Your order #$orderId has been marked as completed. Thank you for your purchase!";
    //     $client->messages->create($phoneNumber, [
    //         'from' => $twilioNumber,
    //         'body' => $message,
    //     ]);
    // }

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
            ->with('user', 'orderItems.product', 'voucher') // Corrected to use 'voucher'
            ->get();

        return view('staff.orders.online-pending', compact('orders'));
    }


    public function posPending()
    {
        $orders = POSOrder::where('status', 'pending')->get();

        return view('staff.orders.pos.pos-pending', compact('orders'));
    }

    public function posCompleted()
    {
        $orders = POSOrder::where('status', 'completed')->get();

        return view('staff.orders.pos.pos-completed', compact('orders'));
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
            ->with('user', 'voucher')
            ->get();

        // Count completed pickup orders with status 'completed' and delivery option 'pick-up'
        $pickUpOrdersCount = Order::where('status', 'completed')
            ->where('delivery_option', 'pick-up')
            ->with('user', 'voucher')
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
}
