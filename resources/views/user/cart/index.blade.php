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
                                            </td>
                                            <td class="shoping__cart__price">
                                                ₱<span class="price">{{ number_format($cart->product->price, 2) }}</span>
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
                                                    class="total">{{ number_format($cart->product->price * $cart->quantity, 2) }}</span>
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
                            <form action="#">
                                <input type="text" placeholder="Enter your coupon code">
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
                                        id="subtotal">{{ $carts->isEmpty()? '0.00': number_format($carts->sum(function ($cart) {return $cart->product->price * $cart->quantity;}),2) }}</span></span>
                            </li>
                            <li>Total <span>₱<span
                                        id="grand-total">{{ $carts->isEmpty()? '0.00': number_format($carts->sum(function ($cart) {return $cart->product->price * $cart->quantity;}),2) }}</span></span>
                            </li>
                        </ul>
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
            // Function to update totals dynamically
            function updateTotal() {
                let subtotal = 0;
                $('tbody tr').each(function() {
                    let price = parseFloat($(this).find('.price').text().replace(/[^0-9.-]+/g, ""));
                    let quantity = parseInt($(this).find('input[name="quantity"]').val());

                    if (!isNaN(price) && !isNaN(quantity)) {
                        let total = price * quantity;
                        $(this).find('.total').text(total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                        subtotal += total;
                    }
                });
                $('#subtotal').text(subtotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                $('#grand-total').text(subtotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            }

            // Update quantity on button click
            $('.qtybtn').on('click', function() {
                let $input = $(this).siblings('input[name="quantity"]');
                let currentVal = parseInt($input.val());
                let newVal;

                // Increment by 2 when the increment button is clicked
                if ($(this).hasClass('inc')) {
                    newVal = currentVal + 1;
                } else {
                    // Decrement by 1 (or ensure it does not go below 1)
                    newVal = currentVal > 1 ? currentVal - 1 : 1;
                }

                $input.val(newVal).trigger('change');
            });

            // Update price and stock when quantity changes
            $('input[name="quantity"]').on('change', function() {
                let productId = $(this).data('product-id');
                let newQuantity = $(this).val();
                $.ajax({
                    url: '/cart/update',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        product_id: productId,
                        quantity: newQuantity
                    },
                    success: function(response) {
                        if (response.success) {
                            updateTotal();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        alert('Error updating cart.');
                    }
                });
            });

            // Remove item from cart and update stock
            $('.remove-item').on('click', function() {
                let productId = $(this).data('product-id');
                $.ajax({
                    url: '/cart/remove',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        product_id: productId
                    },
                    success: function(response) {
                        if (response.success) {
                            window.location.reload();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        alert('Error removing item from cart.');
                    }
                });
            });

            // Initial grand total calculation
            updateTotal();
        });
    </script>



@endsection
