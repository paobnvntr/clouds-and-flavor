<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\POSOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{
    public function dashboard()
    {
        $pendingOrdersCount = Order::where('status', 'pending')->count();
        $pendingPOSOrdersCount = POSOrder::where('status', 'pending')->count();
        $totalEarningsFromOrders = Order::where('status', 'completed')->sum('total_price');
        $totalEarningsFromAddOns = DB::table('orders_add_on')
            ->join('orders', 'orders_add_on.order_id', '=', 'orders.id')
            ->join('add_ons', 'orders_add_on.add_on_id', '=', 'add_ons.id')
            ->where('orders.status', 'completed')
            ->sum(DB::raw('add_ons.price * orders_add_on.quantity'));

        $totalEarnings = $totalEarningsFromOrders + $totalEarningsFromAddOns;
        $formattedEarnings = number_format($totalEarnings, 2);
        $totalOrders = Order::where('status', 'completed')->count();
        $totalPOSOrders = POSOrder::where('status', 'completed')->count();

        return view('staff.dashboard', compact('pendingOrdersCount', 'totalPOSOrders', 'formattedEarnings', 'pendingPOSOrdersCount', 'totalOrders'));
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


    public function posCompleteOrder($id)
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
        // Log the incoming order ID for debugging
        Log::info('Completing POS order with ID: ' . $request->order_id);

        $order = PosOrder::find($request->order_id); // Adjust to your model

        if ($order && $order->status !== 'completed') {
            $order->status = 'completed';
            $order->amount = $order->total_price;
            $order->save();

            return redirect()->back()->with('success', 'Order marked as completed successfully.');
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
