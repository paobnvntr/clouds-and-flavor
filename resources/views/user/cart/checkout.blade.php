@extends('layouts.user.app')

@section('title', 'Checkout')

@section('content')


    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-section set-bg" data-setbg="{{ asset('assets/img/deviceseries.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>Checkout</h2>
                        <div class="breadcrumb__option">
                            <a href="./index.html">Home</a>
                            <span>Checkout</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->


    <section class="checkout spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h6><span class="icon_tag_alt"></span> Have a voucher? <a href="#">Click here</a> to enter your code</h6>
                </div>
            </div>
    
            <div class="checkout__form">
                <h4>Billing Details</h4>
                <form action="{{ route('user.cart.placeOrder') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-lg-8 col-md-6">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="checkout__input">
                                        <p>Name<span>*</span></p>
                                        <input type="text" readonly value="{{ $user->name ?? '' }}">
                                    </div>
                                </div>
    
                                <div class="col-lg-6">
                                    <div class="checkout__input">
                                        <p>Phone Number<span>*</span></p>
                                        <input type="text" name="phone_number" readonly value="{{ $user->phone_number ?? '' }}" required>
                                    </div>
                                </div>
                            </div>
    
                            <div class="checkout__input">
                                <p>Address<span>*</span></p>
                                <input type="text" name="address" class="checkout__input__add" readonly value="{{ $user->address ?? '' }}" required>
                            </div>
    
                            <div class="checkout__input">
                                <p>Order notes<span>*</span></p>
                                <input type="text" name="order_notes" placeholder="Notes about your order, e.g. special notes for delivery.">
                            </div>
                        </div>
    
                        {{-- Your Order --}}
                        <div class="col-lg-4 col-md-6">
                            <div class="checkout__order">
                                <h4>Your Order</h4>
                                <div class="checkout__order__products">Products<span>Unit</span> <span>Total</span></div>
                                <ul>
                                    @foreach ($carts as $cart)
                                        <li>{{$cart->quantity}}x {{ $cart->product->product_name }} 
                                            <span>₱{{ number_format($cart->product->price * $cart->quantity, 2) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="checkout__order__subtotal">Subtotal 
                                    <span>₱{{ number_format($totalPrice, 2) }}</span>
                                </div>
                                <div class="checkout__order__total">Voucher
                                    <span>-₱200.00</span>
                                </div>
                                <div class="checkout__order__total">Total 
                                    <span>₱{{ number_format($totalPrice, 2) }}</span>
                                </div>
                                <div class="checkout__input__checkbox">
                                    <label for="paymaya">
                                        Paymaya
                                        <input type="radio" id="paymaya" name="payment_method" value="paymaya" required>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="checkout__input__checkbox">
                                    <label for="gcash">
                                        Gcash
                                        <input type="radio" id="gcash" name="payment_method" value="gcash" required>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <button type="submit" class="site-btn" {{ empty($user->address) || empty($user->phone_number) ? 'disabled' : '' }}>
                                    PLACE ORDER
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

@endsection
