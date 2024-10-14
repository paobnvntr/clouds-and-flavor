@extends('layouts.user.app')

@section('title', 'Checkout')

@section('content')

    <!-- Flash message for success -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

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
                    <h6><span class="icon_tag_alt"></span> Have a discount? <a href="#" data-toggle="modal"
                            data-target="#voucherModal">Click here</a> to enter your code</h6>
                </div>
            </div>

            <div class="checkout__form">
                <h4>Billing Details</h4>
                <form action="{{ route('user.cart.placeOrder') }}" method="POST">
                    @csrf
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="row">
                        <div class="col-lg-8 col-md-6">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="checkout__input">
                                        <p>Name <span>*</span></p>
                                        <input type="text" name="name" readonly value="{{ $user->name ?? '' }}" >
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

                            <div class="checkout__input">
                                <p>Order notes</p>
                                <input type="text" name="order_notes"
                                    placeholder="Notes about your order, e.g. special notes for delivery.">
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
                                            <span>₱{{ number_format((float) $cart->price * $cart->quantity, 2) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="checkout__order__subtotal">Subtotal
                                    <span>₱{{ $totals['subtotal'] }}</span>
                                </div>

                                <ul>
                                    @foreach ($carts as $cart)  
                                        @if ($cart->addOns->isNotEmpty())
                                            <li class="add-ons">
                                                Add-ons:
                                                @foreach ($cart->addOns as $addOn)
                                                    <div>
                                                        {{ $cart->quantity }}x {{ $addOn->name }}
                                                        <span>₱{{ number_format((float) $addOn->price * $cart->quantity, 2) }}</span>
                                                    </div>
                                                @endforeach
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>

                                @if (isset($totals['addons']) && $totals['addons'] > 0)
                                    <div class="checkout__order__subtotal">Add-ons Total
                                        <span>₱{{ number_format((float) $totals['addons'], 2) }}</span>
                                    </div>
                                @endif

                                <div class="checkout__order__total">Discount
                                    <span>-₱{{ number_format((float) $totals['discount'], 2) }}</span>
                                </div>

                                <div class="checkout__order__total">Total
                                    <span id="grandTotal">₱{{ $totals['grandTotal'] }}</span>
                                </div>
                                <input type="hidden" name="grand_total" id="grandTotalInput"
                                    value="{{ $totals['grandTotal'] }}">
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
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="voucherModalLabel">Apply Voucher</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="voucher-form">
                        <div class="form-group">
                            <label for="voucher_code">Voucher Code</label>
                            <input type="text" name="voucher_code" class="form-control"
                                placeholder="Enter your voucher code" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Apply</button>
                        <button type="button" id="remove-voucher" class="btn btn-danger">Remove Voucher</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Function to hide alert after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.transition = "opacity 0.5s ease"; // Add a fade effect
                alert.style.opacity = 0; // Fade out the alert
                setTimeout(() => alert.remove(), 500); // Remove after fade out
            });
        }, 3000); // 3000 milliseconds = 3 seconds

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
                            alert(data.message);
                        } else {
                            alert(data.message);
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

@endsection
