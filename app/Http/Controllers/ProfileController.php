<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Cart;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use App\Models\User;

class ProfileController extends Controller
{
    public function edit(Request $request): View
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
        return view('profile.edit', [
            'user' => $request->user(),
            'categories' => $categories,
            'cartItems' => $cartItems,
            'totalPrice' => $totalPrice,
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Validate the request fields
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'regex:/^.+@.+\..+$/i',
                Rule::unique(User::class)->ignore($request->user()->id), // Exclude current user from unique check
            ],
            'address' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'digits:11'],
        ]);

        // Update the user's profile
        $request->user()->update($request->validated());

        // If email is changed, reset the email verification timestamp
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $request->user()->update([
            'password' => bcrypt($request->password),
        ]);

        return Redirect::route('profile.edit')->with('success', 'Password changed successfully.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
