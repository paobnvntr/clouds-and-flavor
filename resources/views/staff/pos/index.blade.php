@extends('layouts.staff.app')

@section('title', 'Point of Sale')

@section('content')
<div class="container my-4">
    <h2 class="mb-4 text-center">Point of Sale</h2>

    <div id="flash-message" class="mb-3">
        @if(Session::has('success'))
            <div class="alert alert-success" id="alert-success" role="alert">
                {{ Session::get('success') }}
            </div>
        @endif

        @if(Session::has('error'))
            <div class="alert alert-danger" id="alert-failed" role="alert">
                {{ Session::get('error') }}
            </div>
        @endif
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="mb-3">
                <form action="{{ route('staff.pos.index') }}" method="GET" class="d-flex">
                    <input type="text" name="search" value="{{ request()->get('search') }}" class="form-control me-2"
                        placeholder="Search Products" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>

            <!-- Category Filter -->
            <div class="mb-3">
                <label for="category-filter" class="form-label">Filter by Category:</label>
                <select id="category-filter" class="form-select">
                    <option value="">All Categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="row d-flex flex-wrap" id="product-list">
                @foreach ($products as $product)
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-4 product-item"
                        data-category-id="{{ $product->category_id }}">
                        <div class="product-card text-center border rounded shadow-sm p-3 d-flex flex-column"
                            style="height: 100%;">
                            <img src="{{ asset('/' . $product->image) }}" alt="{{ $product->product_name }}"
                                class="img-fluid mb-2" style="max-height: 180px; object-fit: cover;">
                            <h5 class="mt-2 text-truncate">{{ $product->product_name }}</h5>
                            <span class="fw-bold">₱{{ number_format($product->price, 2) }}</span>
                            <span class="text-muted"><i>Stock: {{ $product->stock }}</i></span>
                            <form action="{{ route('staff.pos.addToCart') }}" method="POST"
                                class="add-to-cart-form mt-auto">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit" class="btn btn-dark mt-2">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="col-lg-4">
            <h4 class="text-center">Cart</h4>
            <ul class="list-group" id="cart-items">
                @forelse ($cartItems as $cartItem)
                    <li class="list-group-item d-flex justify-content-between align-items-center"
                        data-id="{{ $cartItem->id }}">
                        {{ $cartItem->product->product_name }} -
                        <input type="number" class="form-control quantity-input" value="{{ $cartItem->quantity }}" min="1"
                            style="width: 60px; margin-left: 10px;">
                        <span class="item-price"
                            data-price="{{ $cartItem->product->price }}">₱{{ number_format($cartItem->product->price * $cartItem->quantity, 2) }}</span>
                        <button class="btn btn-danger bi bi-trash remove-item"></button>
                    </li>
                @empty
                    <li class="list-group-item text-center">The Cart is empty</li>
                @endforelse
            </ul>

            <div class="mt-3 text-center">
                <strong>Total: ₱<span id="cart-total">{{ number_format($cartTotal, 2) }}</span></strong>
            </div>

            <form action="{{ route('staff.pos.checkout') }}" method="POST" class="mt-3" id="place-order-form">
                @csrf
                <input type="hidden" name="cartItems" id="cart-items-input">
                <button type="submit" class="btn btn-success w-100" id="checkout-button">Checkout</button>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        let successAlert = document.getElementById('alert-success');
        if (successAlert) {
            setTimeout(() => {
                successAlert.style.transition = "opacity 0.5s ease";
                successAlert.style.opacity = 0;
                setTimeout(() => { successAlert.remove(); }, 500);
            }, 3000);
        }

        let failedAlert = document.getElementById('alert-failed');
        if (failedAlert) {
            setTimeout(() => {
                failedAlert.style.transition = "opacity 0.5s ease";
                failedAlert.style.opacity = 0;
                setTimeout(() => { failedAlert.remove(); }, 500);
            }, 3000);
        }
    });

    $(document).ready(function () {
        // Handle the add to cart form submission
        $('.add-to-cart-form').on('submit', function (e) {
            e.preventDefault(); // Prevent the default form submission

            // Add product to cart
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: $(this).serialize(), // Serialize form data
                success: function (response) {
                    updateCart(response.cartItems, response
                        .cartTotal); // Update cart items and total
                },
                error: function (xhr) {
                    alert(xhr.responseJSON.message);
                }
            });
        });

        // Update cart items and total
        function updateCart(cartItems, cartTotal) {
            $('#cart-items').empty(); // Clear existing cart items
            if (cartItems.length === 0) {
                $('#cart-items').append('<li class="list-group-item text-center">The Cart is empty</li>');
            } else {
                $.each(cartItems, function (index, item) {
                    if (item.product) {
                        const itemTotal = item.price * item.quantity; // Calculate total for each item
                        $('#cart-items').append(`
                                <li class="list-group-item d-flex justify-content-between align-items-center" data-id="${item.id}">
                                    ${item.product.product_name} - 
                                    <input type="number" class="form-control quantity-input" value="${item.quantity}" min="1" style="width: 60px; margin-right: 10px;">
                                    <span class="item-price" data-price="${item.price}">₱${itemTotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}</span>
                                    <button class="btn btn-danger bi bi-trash remove-item"></button>
                                </li>
                            `);
                    } else {
                        console.error(`Product is undefined for cart item ID: ${item.id}`);
                    }
                });
            }

            if (typeof cartTotal !== 'string') {
                cartTotal = cartTotal.toString();
            }

            $('#cart-total').text(cartTotal.replace(/\B(?=(\d{3})+(?!\d))/g, ",")); // Update total with commas
        }

        // Update quantity and save to database
        $(document).on('change', '.quantity-input', function () {
            const quantity = $(this).val();
            const cartItemId = $(this).closest('li').data('id');
            const price = $(this).closest('li').find('.item-price').data('price');

            // Update the item total in the UI
            const itemTotal = (price * quantity).toFixed(2);
            $(this).closest('li').find('.item-price').text(
                `₱${itemTotal.replace(/\B(?=(\d{3})+(?!\d))/g, ",")}`); // Add comma formatting

            // Calculate new cart total in the UI
            let newTotal = 0;
            $('.item-price').each(function () {
                const itemPrice = parseFloat($(this).text().replace('₱', '').replace(',', ''));
                newTotal += itemPrice;
            });

            $('#cart-total').text(
                `${newTotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}`); // Update total with commas

            // Send AJAX request to update the cart item in the database
            $.ajax({
                type: 'POST', // Use POST to match your backend route
                url: '{{ route('staff.pos.updateCartItem') }}', // Update this to your correct route
                data: {
                    id: cartItemId, // Pass the cart item ID
                    quantity: quantity, // Pass the updated quantity
                    _token: '{{ csrf_token() }}' // Include CSRF token
                },
                success: function (response) {
                    // Optionally update the cart total here if needed
                    // updateCart(response.cartItems, response.cartTotal); // Update cart items and total
                },
                error: function (xhr) {
                    alert(xhr.responseJSON.message);
                }
            });
        });

        // Handle item removal from cart
        $(document).on('click', '.remove-item', function () {
            const cartItemId = $(this).closest('li').data('id');
            const $item = $(this).closest('li');

            // Remove item via AJAX
            $.ajax({
                type: 'POST', // Use POST
                url: '{{ route('staff.pos.removeCartItem') }}', // Use the named route
                data: {
                    id: cartItemId, // Pass the cart item ID
                    _token: '{{ csrf_token() }}' // Include CSRF token
                },
                success: function (response) {
                    $item.remove(); // Remove the item from the UI
                    updateCart(response.cartItems, response.cartTotal); // Update cart total
                },
                error: function (xhr) {
                    alert(xhr.responseJSON.message);
                }
            });
        });

        // Category filtering
        $('#category-filter').change(function () {
            const selectedCategoryId = $(this).val();

            $('.product-item').each(function () {
                const productCategoryId = $(this).data('category-id');

                if (!selectedCategoryId || productCategoryId == selectedCategoryId) {
                    $(this).show(); // Show product if matches category or all categories
                } else {
                    $(this).hide(); // Hide product if it doesn't match
                }
            });
        });
    });
</script>
@endsection