<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\POSOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

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
                    // Log the error for further investigation
                    Log::error('Error sending SMS: ' . $e->getMessage());

                    // Show a specific error message for debugging
                    return redirect()->back()->with('error', 'Order completed, but SMS could not be sent. Please check logs.');
                }
            } else {
                // No phone number was available
                return redirect()->back()->with('error', 'Order completed, but no phone number available for SMS.');
            }

            return redirect()->back()->with('success', 'Order completed and SMS sent successfully!');
        }

        return redirect()->back()->with('error', 'Order not found or already completed.');
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

    protected function sendSmsNotification($phoneNumber, $orderId, $userId, $message)
    {
        // Get user information
        $user = User::find($userId);

        if ($user && $user->phone_number) {
            // Twilio credentials from .env
            $sid = env('TWILIO_SID');
            $token = env('TWILIO_AUTH_TOKEN');
            $twilio_number = env('TWILIO_PHONE_NUMBER');

            Log::info('Twilio SID: ' . $sid);
            Log::info('Twilio Token: ' . ($token ? 'Token found' : 'No Token found'));

            $client = new Client($sid, $token);

            // Ensure phone number is in E.164 format
            $formattedNumber = $this->formatPhoneNumber($user->phone_number);

            // Try to send the SMS
            try {
                $client->messages->create(
                    $formattedNumber,
                    [
                        'from' => $twilio_number,
                        'body' => "Hi {$user->name}, {$message} Thank you for ordering!"
                    ]
                );
            } catch (\Exception $e) {
                Log::error("Failed to send SMS to {$formattedNumber}: " . $e->getMessage());
                throw $e;
            }
        } else {
            Log::error("User or phone number not found for User ID: {$userId}");
        }
    }


    public function toDeliver(Request $request)
    {
        $order = Order::find($request->order_id);

        if ($order && $order->status === 'pending') {
            // Send SMS notification for "To Deliver" status
            $phoneNumber = $order->phone_number;
            if ($phoneNumber) {
                try {
                    $this->sendSmsNotification($phoneNumber, $order->id, $order->user_id, "Your order is on the way!");
                } catch (\Exception $e) {
                    Log::error('Error sending SMS: ' . $e->getMessage());
                }
            }
            return redirect()->back()->with('success', 'SMS sent: Order is on the way.');
        }

        return redirect()->back()->with('error', 'Order not found or already processed.');
    }

    public function delivered(Request $request)
    {
        $order = Order::find($request->order_id);

        if ($order && $order->status === 'pending') {
            // Mark the order as completed
            $order->status = 'completed';
            $order->save();

            // Send SMS notification for "Delivered"
            $phoneNumber = $order->phone_number;
            if ($phoneNumber) {
                try {
                    $this->sendSmsNotification($phoneNumber, $order->id, $order->user_id, "Your order has been successfully delivered.");
                } catch (\Exception $e) {
                    Log::error('Error sending SMS: ' . $e->getMessage());
                }
            }
            return redirect()->back()->with('success', 'Order completed and SMS sent successfully.');
        }

        return redirect()->back()->with('error', 'Order not found or already processed.');
    }

    public function readyForPickup(Request $request)
    {
        $order = Order::find($request->order_id);

        if ($order && $order->status === 'pending') {
            // Send SMS notification for "Ready for Pick-Up"
            $phoneNumber = $order->phone_number;
            if ($phoneNumber) {
                try {
                    $this->sendSmsNotification($phoneNumber, $order->id, $order->user_id, "Your order is ready for pick-up.");
                } catch (\Exception $e) {
                    Log::error('Error sending SMS: ' . $e->getMessage());
                }
            }
            return redirect()->back()->with('success', 'Order is ready for pick-up and SMS sent.');
        }

        return redirect()->back()->with('error', 'Order not found or already processed.');
    }

    public function completePickup(Request $request)
    {
        $order = Order::find($request->order_id);

        if ($order && $order->status === 'pending') {
            // Mark the order as completed
            $order->status = 'completed';
            $order->save();

            // Send SMS notification for "Order Picked Up"
            $phoneNumber = $order->phone_number;
            if ($phoneNumber) {
                try {
                    $this->sendSmsNotification($phoneNumber, $order->id, $order->user_id, "Your order has been picked up successfully.");
                } catch (\Exception $e) {
                    Log::error('Error sending SMS: ' . $e->getMessage());
                }
            }
            return redirect()->back()->with('success', 'Order picked up and SMS sent successfully.');
        }

        return redirect()->back()->with('error', 'Order not found or already processed.');
    }


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
}
