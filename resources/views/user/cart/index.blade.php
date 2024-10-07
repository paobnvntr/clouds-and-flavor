@extends('layouts.user.app')

@section('title', 'Your Cart')

@section('content')

    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <!-- Flash message for success -->
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
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
                    }, 3000); // 5000 milliseconds = 5 seconds
                </script>
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">My Cart</h3>
                    </div>

                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">My Cart</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                <div class="row">
                    <table id="datatablesSimple" class="table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Date & Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($carts->isEmpty())
                                <tr>
                                    <td colspan="6" class="text-center">Your cart is empty.</td>
                                </tr>
                            @else
                                @foreach ($carts as $cart)
                                    <tr>
                                        <td>{{ $cart->product->product_name }}</td>
                                        <td>₱<span class="price">{{ number_format($cart->product->price, 2) }}</span></td>
                                        <td>
                                            <input type="number" name="quantity" class="quantity"
                                                data-product-id="{{ $cart->id }}" value="{{ $cart->quantity }}"
                                                min="1">
                                        </td>
                                        <td>₱<span
                                                class="total">{{ number_format($cart->product->price * $cart->quantity, 2) }}</span>
                                        </td>
                                        <td>{{ $cart->created_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                            <button class="btn btn-danger btn-sm remove-item"
                                                data-product-id="{{ $cart->id }}">Remove</button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>

                    <div class="text-end">
                        <strong>Total: ₱<span id="grand-total">{{ $carts->isEmpty() ? '0.00' : number_format($carts->sum(function ($cart) {
                            return $cart->product->price * $cart->quantity;
                        }), 2) }}</span></strong>
                        <br>
                        <a href="{{ route('user.cart.checkout') }}" class="btn btn-primary mt-3">Proceed to Checkout</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Function to update totals dynamically
            function updateTotal() {
                let grandTotal = 0;
                $('tbody tr').each(function() {
                    let price = parseFloat($(this).find('.price').text().replace(/[^0-9.-]+/g, ""));
                    let quantity = parseInt($(this).find('.quantity').val());
                    let total = price * quantity;

                    $(this).find('.total').text(total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    grandTotal += total;
                });
                $('#grand-total').text(grandTotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            }

            // Update price and stock when quantity changes
            $('.quantity').on('change', function() {
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
