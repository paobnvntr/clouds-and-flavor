<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderAddOn;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Twilio\Rest\Client;

class OrderController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', 0)->get();
        $cartItems = Cart::where('user_id', Auth::id())->count();
        $carts = Cart::where('user_id', Auth::id())->with(['product', 'addOns'])->get();

        $subtotal = $carts->sum(function ($cart) {
            $productPrice = $cart->product->on_sale ? $cart->product->sale_price : $cart->product->price;
            return round($productPrice * $cart->quantity, 2);
        });

        $addonsTotal = $carts->sum(function ($cart) {
            return $cart->addOns->sum(function ($addOn) use ($cart) {
                return round($addOn->price * $cart->quantity, 2);
            });
        });

        $totalPrice = round($subtotal + $addonsTotal, 2);

        $orders = Order::with(['orderItems.product', 'orderAddOns.addOn', 'voucher'])
            ->where('user_id', Auth::id())
            ->get();

        return view('user.order.index', compact('orders', 'categories', 'cartItems', 'totalPrice'));
    }

    public function payOrder(Request $request)
    {
        $order = Order::find($request->order_id);

        if ($order && $order->payment_status == 'unpaid') {
            // Update the order payment status and reference number
            $order->payment_status = 'paid';
            $order->reference_number = $request->reference_number;

            // Check and update delivery option if present
            if ($request->has('delivery_option')) {
                $order->delivery_option = $request->delivery_option;
            }

            $order->save();

            // Get the user's phone number from the order
            $userId = $order->user_id;
            $phoneNumber = User::find($userId)->phone_number;

            // Send SMS notification if phone number exists
            if ($phoneNumber) {
                try {
                    $this->sendSmsNotification($phoneNumber, $order->id, $userId, $order->total_price);
                } catch (\Exception $e) {
                    // Log the error for further investigation
                    Log::error('Error sending SMS: ' . $e->getMessage());
                }
            } else {
                // Log if no phone number is available
                Log::error('No phone number available for User ID: ' . $userId);
            }

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
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

    protected function sendSmsNotification($phoneNumber, $orderId, $userId, $totalPrice)
    {
        // Get user information
        $user = User::find($userId);

        if ($user && $user->phone_number) {
            // Twilio credentials from .env
            $sid = env('TWILIO_SID');
            $token = env('TWILIO_AUTH_TOKEN');
            $twilio_number = env('TWILIO_PHONE_NUMBER');

            Log::info('Twilio SID: ' . $sid);
            Log::info('Twilio Token: ' . $token ? 'Token found' : 'No Token found');

            $client = new Client($sid, $token);

            // Ensure phone number is in E.164 format
            $formattedNumber = $this->formatPhoneNumber($user->phone_number);

            // Compose the SMS message with the total price
            $message = "Hi {$user->name}, your order has been successfully paid. The total amount is {$totalPrice}. Thank you for ordering!";

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
                // Log any exceptions during SMS sending
                Log::error("Failed to send SMS to {$formattedNumber}: " . $e->getMessage());

                // Re-throw exception to be handled in the completeOrder method
                throw $e;
            }
        } else {
            // Log missing user information or phone number
            Log::error("User or phone number not found for User ID: {$userId}");
        }
    }


    private function calculateDiscount($total, $voucher)
    {
        if ($voucher->discount_type == 'percentage') {
            return $total * ($voucher->discount / 100);
        } else {
            return min($voucher->discount, $total);
        }
    }

    public function placeOrder(Request $request)
    {
        $request->merge([
            'grand_total' => str_replace(',', '', $request->input('grand_total'))
        ]);

        $validator = Validator::make($request->all(), [
            '_token' => 'required',
            'name' => 'required|string|max:255',
            'phone_number' => 'required|regex:/^09[0-9]{9}$/i',
            'address' => 'required|string|max:255',
            'grand_total' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('user.cart.index')->with('failed', 'Invalid input.');
        }

        $carts = Cart::where('user_id', Auth::id())->with(['product', 'addOns'])->get();

        if ($carts->isEmpty()) {
            return redirect()->route('user.cart.index')->with('failed', 'Your cart is empty.');
        }

        $subtotal = $carts->sum(function ($cart) {
            $productPrice = $cart->product->on_sale ? $cart->product->sale_price : $cart->product->price;
            return round($productPrice * $cart->quantity, 2);
        });

        $addonsTotal = $carts->sum(function ($cart) {
            return $cart->addOns->sum(function ($addOn) use ($cart) {
                return round($addOn->price * $cart->quantity, 2);
            });
        });

        $totalBeforeDiscount = round($subtotal + $addonsTotal, 2);
        $appliedVoucher = session('applied_voucher');

        $discount = 0;
        if ($appliedVoucher) {
            $voucher = Voucher::find($appliedVoucher->getKey());
            if ($voucher && $voucher->is_active) {
                $discount = $this->calculateDiscount($totalBeforeDiscount, $voucher);
                $discount = round($discount, 2);
            } else {
                session()->forget('applied_voucher');
            }
        }

        $grandTotal = round(max($totalBeforeDiscount - $discount, 0), 2);

        if (abs($grandTotal - $request->grand_total) > 0.01) {
            return redirect()->route('user.cart.index')->with('failed', 'Order total mismatch. Please try again.');
        }

        DB::beginTransaction();

        try {
            $orderData = [
                'user_id' => Auth::id(),
                'address' => $request->address,
                'phone_number' => $request->phone_number,
                'payment_method' => $request->payment_method,
                'total_price' => $grandTotal,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'status' => 'pending',
            ];

            if ($appliedVoucher) {
                $voucher = Voucher::find($appliedVoucher->getKey());
                if ($voucher) {
                    $orderData['voucher_id'] = $voucher->id;
                    $voucher->increment('times_used');
                    if ($voucher->usage_limit && $voucher->times_used >= $voucher->usage_limit) {
                        $voucher->update(['is_active' => false]);
                    }
                }
            }

            $order = Order::create($orderData);

            foreach ($carts as $cart) {
                $product = $cart->product;

                if ($product->stock < $cart->quantity) {
                    throw new \Exception('Insufficient stock for ' . $product->product_name);
                }

                $product->stock -= $cart->quantity;
                $product->save();

                $addOrder = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cart->product_id,
                    'quantity' => $cart->quantity,
                    'price' => round($product->on_sale ? $product->sale_price : $product->price, 2),
                ]);

                if (!$addOrder) {
                    throw new \Exception('Failed to add order item for ' . $product->product_name);
                }

                if ($cart->addOns && $cart->addOns->isNotEmpty()) {
                    foreach ($cart->addOns as $addOn) {
                        $addAddOn = OrderAddOn::create([
                            'order_id' => $order->id,
                            'add_on_id' => $addOn->id,
                            'price' => round($addOn->price, 2),
                            'quantity' => $cart->quantity,
                        ]);

                        if (!$addAddOn) {
                            throw new \Exception('Failed to add order add-on for ' . $addOn->add_on_name);
                        }
                    }
                }
            }

            Cart::where('user_id', Auth::id())->delete();
            session()->forget('applied_voucher');
            DB::commit();

            return redirect()->route('user.order.index')->with('success', 'Order placed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order Placement Error:', ['error' => $e->getMessage()]);
            return redirect()->route('user.cart.index')->with('failed', $e->getMessage());
        }
    }
}
