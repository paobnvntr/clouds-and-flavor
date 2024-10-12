@extends('layouts.user.app')

@section('title', 'Your Cart')

@section('content')

    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-section set-bg" data-setbg="{{ asset('assets/img/deviceseries.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>My Cart</h2>
                        <div class="breadcrumb__option">
                            <a href="dashboard">Home</a>
                            <span>Cart</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Shoping Cart Section Begin -->
    <section class="shoping-cart spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="shoping__cart__table">
                        <table>
                            <thead>
                                <tr>
                                    <th class="shoping__product">Products</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($carts->isEmpty())
                                    <tr>
                                        <td colspan="5" class="text-center">Your cart is empty.</td>
                                    </tr>
                                @else
                                    @foreach ($carts as $cart)
                                        <tr>
                                            <td class="shoping__cart__item">
                                                <img src="{{ asset('/' . $cart->product->image) }}" alt="">
                                                <h5>{{ $cart->product->product_name }}</h5>
                                                @if ($cart->product->on_sale)
                                                    <span style="color:red;">On Sale!</span>
                                                    <br>
                                                    <del>₱{{ number_format($cart->product->price, 2) }}</del>
                                                    <br>
                                                    <strong>₱{{ number_format($cart->product->sale_price, 2) }}</strong>
                                                @else
                                                    <span>₱{{ number_format($cart->product->price, 2) }}</span>
                                                @endif
                                            </td>
                                            <td class="shoping__cart__price">
                                                ₱<span
                                                    class="price">{{ number_format($cart->product->on_sale ? $cart->product->sale_price : $cart->product->price, 2) }}</span>
                                            </td>
                                            <td class="shoping__cart__quantity">
                                                <div class="quantity">
                                                    <div class="pro-qty1">
                                                        <button class="dec qtybtn" style="border: none;">-</button>
                                                        <input type="text" name="quantity" class="quantity"
                                                            data-product-id="{{ $cart->id }}"
                                                            value="{{ $cart->quantity }}" min="1">
                                                        <button class="inc qtybtn" style="border: none;">+</button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="shoping__cart__total">
                                                ₱<span
                                                    class="total">{{ number_format(($cart->product->on_sale ? $cart->product->sale_price : $cart->product->price) * $cart->quantity, 2) }}</span>
                                            </td>
                                            <td class="shoping__cart__item__close">
                                                <button class="btn btn-danger btn-sm remove-item"
                                                    data-product-id="{{ $cart->id }}">
                                                    <span class="icon_close"></span>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="shoping__cart__btns">
                        <a href="/products" class="primary-btn cart-btn">CONTINUE SHOPPING</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="shoping__continue">
                        <div class="shoping__discount">
                            <h5>Discount Codes</h5>
                            <form id="voucher-form">
                                @csrf
                                <input type="text" name="voucher_code" placeholder="Enter your coupon code">
                                <button type="submit" class="site-btn">APPLY COUPON</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="shoping__checkout">
                        <h5>Cart Total</h5>
                        <ul>
                            <li>Subtotal <span>₱<span
                                        id="subtotal">{{ number_format($totals['subtotal'], 2) }}</span></span></li>
                            @if (session('applied_voucher'))
                                <li>Discount <span>-₱<span
                                            id="discount">{{ number_format($totals['discount'], 2) }}</span></span></li>
                            @endif
                            <li>Total <span>₱<span
                                        id="grand-total">{{ number_format($totals['grandTotal'], 2) }}</span></span></li>
                        </ul>
                        @if (session('applied_voucher'))
                            <p>Voucher applied: {{ session('applied_voucher')->code }}
                                <button id="remove-voucher" class="btn btn-sm btn-danger">Remove</button>
                            </p>
                        @endif
                        <a href="{{ route('user.cart.checkout') }}" class="primary-btn">PROCEED TO CHECKOUT</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Shoping Cart Section End -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            function updateTotal() {
                let subtotal = 0;
                $('.shoping__cart__total').each(function() {
                    subtotal += parseFloat($(this).find('.total').text().replace(/[^0-9.-]+/g, ""));
                });
                $('#subtotal').text(subtotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));

                // Fetch the current discount from the server
                $.get('/cart/get-totals', function(response) {
                    // Ensure discount and grandTotal are numbers
                    let discount = parseFloat(response.discount) || 0;
                    let grandTotal = parseFloat(response.grandTotal) || 0;

                    $('#discount').text(discount.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    $('#grand-total').text(grandTotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                });
            }

            $('.qtybtn').on('click', function() {
                let $input = $(this).siblings('input[name="quantity"]');
                let currentVal = parseInt($input.val());
                let newVal = $(this).hasClass('inc') ? currentVal + 1 : (currentVal > 1 ? currentVal - 1 : 1);
                $input.val(newVal).trigger('change');
            });

            $('input[name="quantity"]').on('change', function() {
                let $row = $(this).closest('tr');
                let cartId = $(this).data('product-id');
                let newQuantity = $(this).val();
                $.ajax({
                    url: '/cart/update',
                    type: 'POST',
                    data: {
                        product_id: cartId,
                        quantity: newQuantity,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            $row.find('.shoping__cart__quantity input').val(response.newQuantity);
                            $row.find('.shoping__cart__total .total').text(response.newTotalPrice);
                            updateTotal();
                        } else {
                            console.error(response.message);
                            $row.find('.shoping__cart__quantity input').val($row.find('.shoping__cart__quantity input').data('original-quantity'));
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseJSON.message);
                        $row.find('.shoping__cart__quantity input').val($row.find('.shoping__cart__quantity input').data('original-quantity'));
                    }
                });
            });

            $('.remove-item').on('click', function() {
                let $row = $(this).closest('tr');
                let cartId = $(this).data('product-id');
                $.ajax({
                    url: '/cart/remove',
                    type: 'POST',
                    data: {
                        product_id: cartId,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            $row.remove();
                            updateTotal();
                        } else {
                            console.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseJSON.message);
                    }
                });
            });

            $('#voucher-form').on('submit', function(e) {
                e.preventDefault();
                let voucherCode = $(this).find('input[name="voucher_code"]').val();
                $.ajax({
                    url: '/cart/apply-voucher',
                    type: 'POST',
                    data: {
                        voucher_code: voucherCode,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            updateTotal();
                            // Optionally reload the page to refresh the cart totals
                            location.reload();
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseJSON.message);
                    }
                });
            });

            $('#remove-voucher').on('click', function() {
                $.ajax({
                    url: '/cart/remove-voucher',
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            updateTotal();
                            // Optionally reload the page to refresh the cart totals
                            location.reload();
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseJSON.message);
                    }
                });
            });
        });
    </script>
@endsection
