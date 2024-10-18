@extends('layouts.user.app')

@section('title', 'Clouds N Flavor | Checkout')

@section('content')

    <section class="hero hero-normal">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="hero__categories">
                        <div class="hero__categories__all">
                            <i class="fa fa-bars"></i>
                            <span>Categories</span>
                        </div>
                        <ul>
                            <li>
                                <a href="{{ route('user.products.index') }}">All Products</a>
                            </li>
                            @foreach ($categories as $category)
                                <li>
                                    <a href="{{ route('user.products.index', ['category_id' => $category->id]) }}">
                                        {{ $category->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="col-lg-9">
                    <div class="row">
                        <div class="hero__search col-8">
                            <div class="hero__search__form col-12">
                                <form action="{{ route('user.products.index') }}" method="GET">
                                    <input type="text" name="search" placeholder="Search products" />
                                    <button type="submit" class="site-btn">SEARCH</button>
                                </form>
                            </div>
                        </div>

                        <div class="header__cart col-4">
                            <ul>
                                <li>
                                    <a href="{{ url('/my-cart') }}">
                                        <i class="fa fa-shopping-cart"></i>
                                        <span>{{ $cartItems }}</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="header__cart__price">Total: <span>₱
                                    {{ number_format($totalBeforeDiscount, 2) }}</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="breadcrumb-section set-bg" data-setbg="{{ asset('assets/img/deviceseries.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>Checkout</h2>
                        <div class="breadcrumb__option">
                            <a href="{{ url('/dashboard') }}d">Home</a>
                            <a href="{{ url('/my-cart') }}">My Cart</a>
                            <span>Checkout</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if (empty($user->address) || empty($user->phone_number))
        <div class="alert alert-warning floating-alert" style="z-index: 9999;">
            <strong>Notice:</strong> Please update your address and phone number on your profile to place an order.
        </div>
    @endif

    <section class="checkout spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h6><span class="icon_tag_alt"></span> Have a discount? <a href="#" data-toggle="modal"
                            data-target="#voucherModal">Click here</a> to enter your code</h6>
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
                                        <p>Name <span>*</span></p>
                                        <input type="text" name="name" readonly value="{{ $user->name ?? '' }}">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="checkout__input">
                                        <p>Phone Number <span>*</span></p>
                                        <input type="text" name="phone_number" readonly
                                            value="{{ $user->phone_number ?? '' }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="checkout__input">
                                <p>Address <span>*</span></p>
                                <input type="text" name="address" class="checkout__input__add" readonly
                                    value="{{ $user->address ?? '' }}" required>
                            </div>
                        </div>

                        {{-- Your Order --}}
                        <div class="col-lg-4 col-md-6">
                            <div class="checkout__order">
                                <h4>Your Order</h4>
                                <div class="checkout__order__products">
                                    Products
                                    <span>Total</span>

                                </div>
                                <ul>
                                    @foreach ($carts as $cart)
                                        <li>
                                            {{ $cart->quantity }}x {{ $cart->product->product_name }}
                                            <span>₱ {{ number_format((float) $cart->price * $cart->quantity, 2) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="checkout__order__subtotal">Subtotal
                                    <span>₱ {{ $totals['subtotal'] }}</span>
                                </div>

                                <ul>
                                    @foreach ($carts as $cart)
                                        @if ($cart->addOns->isNotEmpty())
                                            <li class="add-ons">
                                                Add-ons:
                                                @foreach ($cart->addOns as $addOn)
                                                    <div>
                                                        {{ $cart->quantity }}x {{ $addOn->name }}
                                                        <span>₱
                                                            {{ number_format((float) $addOn->price * $cart->quantity, 2) }}</span>
                                                    </div>
                                                @endforeach
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>

                                @if (isset($totals['addons']) && $totals['addons'] > 0)
                                    <div class="checkout__order__subtotal">Add-ons Total
                                        <span>₱ {{ number_format((float) $totals['addons'], 2) }}</span>
                                    </div>
                                @endif

                                <div class="checkout__order__discount">Discount
                                    <span>- ₱ {{ number_format((float) $totals['discount'], 2) }}</span>
                                </div>

                                <div class="checkout__order__total">Total
                                    <span id="grandTotal">₱ {{ $totals['grandTotal'] }}</span>
                                </div>
                                <input type="hidden" name="grand_total" id="grandTotalInput"
                                    value="{{ $totals['grandTotal'] }}">

                                <h5 class="font-weight-bold mb-3">Payment Mode</h5>
                                <div class="checkout__input__checkbox ml-2">
                                    <label for="paymaya">
                                        PayMaya
                                        <input type="radio" id="paymaya" name="payment_method" value="PayMaya" required>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="checkout__input__checkbox ml-2">
                                    <label for="gcash">
                                        GCash
                                        <input type="radio" id="gcash" name="payment_method" value="Gcash" required>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <button type="submit" class="site-btn"
                                    {{ empty($user->address) || empty($user->phone_number) ? 'disabled' : '' }}>
                                    PLACE ORDER
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Voucher Modal -->
    <div class="modal fade" id="voucherModal" tabindex="-1" role="dialog" aria-labelledby="voucherModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold text-success" id="voucherModalLabel">Apply Voucher</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="voucher-form">
                        <div class="form-group">
                            <label for="voucher_code">Voucher Code:</label>
                            <input type="text" name="voucher_code" class="form-control" id="voucher_code"
                                placeholder="Enter your voucher code" required>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-success">Apply</button>
                            <button type="button" id="remove-voucher" class="btn btn-outline-danger">Remove
                                Voucher</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // AJAX for applying and removing vouchers
        document.addEventListener('DOMContentLoaded', function() {
            function updateTotal(data) {
                document.querySelector('.checkout__order__subtotal:nth-of-type(1) span').textContent =
                    `₱${formatNumber(data.subtotal)}`;
                document.querySelector('.checkout__order__subtotal:nth-of-type(2) span').textContent =
                    `₱${formatNumber(data.addons)}`;
                document.querySelector('.checkout__order__total:nth-of-type(1) span').textContent =
                    `-₱${formatNumber(data.discount)}`;
                document.querySelector('#grandTotal').textContent = `₱${formatNumber(data.grandTotal)}`;
                document.querySelector('#grandTotalInput').value = data.grandTotal;
            }

            function formatNumber(num) {
                return parseFloat(num).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }

            // Handle voucher application
            document.querySelector('#voucher-form').addEventListener('submit', function(e) {
                e.preventDefault();
                let voucherCode = this.querySelector('input[name="voucher_code"]').value;
                fetch('/cart/apply-voucher', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-Token': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            voucher_code: voucherCode
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            updateTotal(data);
                            const flashMessage = document.getElementById('alert-success');
                            if (flashMessage) {
                                flashMessage.innerText = data.message;
                                flashMessage.style.display = 'block';
                                setTimeout(() => {
                                    flashMessage.style.display = 'none';
                                }, 3000);
                            }
                        } else {
                            const flashMessage = document.getElementById('alert-failed');
                            if (flashMessage) {
                                flashMessage.innerText = data.message;
                                flashMessage.style.display = 'block';
                                setTimeout(() => {
                                    flashMessage.style.display = 'none';
                                }, 3000);
                            }
                        }
                    });
            });
            // Handle voucher removal
            document.querySelector('#remove-voucher').addEventListener('click', function() {
                fetch('/cart/remove-voucher', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-Token': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            updateTotal(data);
                            alert(data.message);
                        } else {
                            alert(data.message);
                        }
                    });
            });
        });
    </script>
    <style>
        .floating-alert {
            text-align: center;
            z-index: 1000;
            padding: 15px;
            background-color: #f0ad4e;
            color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: opacity 0.5s ease;
        }
    </style>
@endsection
