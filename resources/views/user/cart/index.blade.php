@extends('layouts.user.app')

@section('title', 'Clouds N Flavor | My Cart')

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
                        <div class="hero__search col-lg-8 col-md-12">
                            <div class="hero__search__form col-12">
                                <form action="{{ route('user.products.index') }}" method="GET">
                                    <input type="text" name="search" placeholder="Search products" />
                                    <button type="submit" class="site-btn">SEARCH</button>
                                </form>
                            </div>
                        </div>

                        <div class="header__cart col-4">
                            <!-- <ul>
                                    <li>
                                        <a href="{{ url('/my-cart') }}">
                                            <i class="fa fa-shopping-cart"></i>
                                            <span>{{ $cartItems }}</span>
                                        </a>
                                    </li>
                                </ul>
                                <div class="header__cart__price">Total: <span>₱ {{ number_format($totalPrice, 2) }}</span></div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

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

    @if (session('error'))
        <div class="alert alert-danger"
            style="position: fixed; top: 10%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;">
            {{ session('error') }}
        </div>
    @endif

    @if (session('message'))
        <div class="alert alert-success"
            style="position: fixed; top: 10%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;">
            {{ session('message') }}
        </div>
    @endif

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
    </script>

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
                                    <th>Additional</th>
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
                                            <td class="shoping__cart__item text-center">
                                                <img src="{{ asset('/' . $cart->product->image) }}" alt=""
                                                    class="cart-product-image img-thumbnail">
                                                <br>
                                                <h5 class="font-weight-bolder">{{ $cart->product->product_name }}</h5>
                                                @if ($cart->product->on_sale)
                                                    <span style="color:red;">On Sale!</span>
                                                    <br>
                                                    <del
                                                        style="color:red;">₱{{ number_format($cart->product->price, 2) }}</del>
                                                    <br>
                                                    <strong>₱{{ number_format($cart->product->sale_price, 2) }}</strong>
                                                @else
                                                    <span></span>
                                                    <br>
                                                    <strong>₱{{ number_format($cart->product->price, 2) }}</strong>
                                                @endif
                                            </td>
                                            <td class="shoping__cart__item">
                                                <div class="add-ons-section">
                                                    @if ($cart->addOns->count() > 0)
                                                        <ul style="list-style-type: none;">
                                                            @foreach ($cart->addOns as $addon)
                                                                <li class="font-weight-bolder text-center">
                                                                    {{ $addon->name }}
                                                                    (₱{{ number_format($addon->price, 2) }})
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @else
                                                        <p class="font-weight-bolder text-center">No add-ons added.</p>
                                                    @endif
                                                </div>
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
                                                        <!-- hidden input for stock -->
                                                        <input type="hidden" class="stock" data-stock="{{ $cart->product->stock }}">
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
                    <div class="shoping__cart__btns d-flex justify-content-end">
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
                        <h5>Cart Total Summary</h5>
                        <ul>
                            <li>Subtotal <span class="text-secondary">₱<span id="subtotal"
                                        class="text-secondary">{{ number_format($totals['subtotal'], 2) }}</span></span>
                            </li>
                            <li>Add-ons <span class="text-secondary">₱<span id="addons-total"
                                        class="text-secondary">{{ number_format($totals['addons'], 2) }}</span></span></li>
                            <li id="discount-row" style="display: none;">Discount <span>-₱<span
                                        id="discount">0.00</span></span></li>
                            <li>Total <span class="text-success">₱<span id="grand-total"
                                        class="text-success">{{ number_format($totals['grandTotal'], 2) }}</span></span>
                            </li>
                        </ul>
                        <div id="voucher-info" style="display: none;">
                            <hr>
                            <div class="d-flex align-items-center justify-content-between">
                                <p>
                                    <strong class="text-dark">Voucher applied:</strong> <span
                                        id="applied-voucher-code"></span>
                                </p>
                                <button id="remove-voucher" class="btn btn-sm btn-danger mb-3">Remove</button>
                            </div>
                        </div>
                        <a href="{{ route('user.cart.checkout') }}" class="primary-btn">PROCEED TO CHECKOUT</a>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- Shoping Cart Section End -->

    <style>
        @media screen and (max-width: 768px) {
            .hero-normal {
                padding-bottom: 0 !important;
            }

            .product {
                padding-top: 30px !important;
            }

            table th,
            table td {
                padding: 12px 10px;
                /* Increase padding */
            }

            /* Optionally, make the table scrollable for very small screens */
            .shoping__table {
                display: block;
                width: 100%;
                overflow-x: auto;
            }

            /* Adjust column width and make them more spacious */
            .shoping__product {
                width: 35%;
                /* Make products column larger */
            }

            .shoping__product~th,
            .shoping__product~td {
                width: auto;
                /* Spread other columns evenly */
            }

            /* If needed, center the text */
            table th,
            table td {
                text-align: center;
            }
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            function updateTotal() {
                $.get('/cart/get-totals', function(response) {
                    $('#subtotal').text(formatCurrency(response.subtotal));
                    $('#addons-total').text(formatCurrency(response.addons));
                    $('#grand-total').text(formatCurrency(response.grandTotal));

                    if (response.discount > 0) {
                        $('#discount').text(formatCurrency(response.discount));
                        $('#discount-row').show();
                    } else {
                        $('#discount-row').hide();
                    }

                    if (response.appliedVoucher) {
                        $('#applied-voucher-code').text(response.appliedVoucher.code);
                        $('#voucher-info').show();
                    } else {
                        $('#voucher-info').hide();
                    }
                });
            }

            function formatCurrency(value) {
                return parseFloat(value).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }

            $('.qtybtn').on('click', function() {
                let $input = $(this).siblings('input[name="quantity"]');
                let currentVal = parseInt($input.val());
                let newVal = $(this).hasClass('inc') ? currentVal + 1 : (currentVal > 1 ? currentVal - 1 :
                    1);

                let stock = parseInt($input.siblings('.stock').data('stock'));

                // show stock current val and new val
                console.log(stock, currentVal, newVal);

                if (stock > 0 && newVal <= stock) {
                    $input.val(newVal).trigger('change');

                    let price = $(this).closest('tr').find('.price').text();
                    let total = newVal * parseFloat(price.replace(/,/g, ''));
                    $(this).closest('tr').find('.total').text(formatCurrency(total));
                } else {
                    alert('This product is out of stock.');
                }
            });


            $('input[name="quantity"]').on('change', function() {
                let productId = $(this).data('product-id');
                let newQuantity = $(this).val();
                $.ajax({
                    url: '/cart/update',
                    method: 'POST',
                    data: {
                        product_id: productId,
                        quantity: newQuantity,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            updateTotal();
                        } else {
                            alert(response.message);

                            let availableStock = response.availableStock ||
                                1;
                            $('input[data-product-id="' + productId + '"]').val(availableStock);
                        }
                    },
                    error: function(response) {
                        console.log('Error:', response);
                    }
                });
            });

            $('.remove-item').on('click', function() {
                let productId = $(this).data('product-id');
                $.ajax({
                    url: '/cart/remove',
                    method: 'POST',
                    data: {
                        product_id: productId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        location.reload();
                    }
                });
            });

            // Voucher form submission
            $('#voucher-form').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: '/cart/apply-voucher',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        alert(response.message);
                        if (response.success) {
                            updateTotal();
                        }
                    }
                });
            });

            // Remove voucher
            $('#remove-voucher').on('click', function() {
                $.ajax({
                    url: '/cart/remove-voucher',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alert(response.message);
                        if (response.success) {
                            updateTotal();
                        }
                    }
                });
            });

            // Initial calculation
            updateTotal();
        });
    </script>


@endsection
