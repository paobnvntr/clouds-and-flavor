@extends('layouts.user.app')

@section('title', 'Checkout')

@section('content')

    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Checkout</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Checkout</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                <h4>Order Summary</h4>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($carts as $cart)
                            <tr>
                                <td>{{ $cart->product->product_name }}</td>
                                <td>{{ $cart->quantity }}</td>
                                <td>₱{{ number_format($cart->product->price, 2) }}</td>
                                <td>₱{{ number_format($cart->product->price * $cart->quantity, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="text-end">
                    <h4>Total: ₱{{ number_format($totalPrice, 2) }}</h4>
                </div>

                <!-- Checkout Form -->
                <form action="{{ route('user.cart.placeOrder') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="address" name="address"
                           readonly value="{{ $user->address ?? '' }}" required {{ empty($user->address) ? 'disabled' : '' }}>
                    </div>
                    <div class="mb-3">
                        <label for="phone_number" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone_number" name="phone_number"
                            readonly value="{{ $user->phone_number ?? '' }}" required
                            {{ empty($user->phone_number) ? 'disabled' : '' }}>
                    </div>
                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Payment Method</label>
                        <select class="form-select" id="payment_method" name="payment_method" required>
                            <option value="" disabled selected>Select Payment Method</option>
                            <option value="cash_on_delivery">Cash on Delivery</option>
                            <option value="pickup">Pick-Up</option>
                            <option value="gcash">GCash</option>
                            <option value="paymaya">PayMaya</option>
                        </select>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-success mt-3"
                            {{ empty($user->address) || empty($user->phone_number) ? 'disabled' : '' }}>
                            Place Order
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </main>

@endsection
