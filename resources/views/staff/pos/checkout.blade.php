@extends('layouts.staff.app')

@section('title', 'Checkout')

@section('content')
    <div class="container">
        <h2>Checkout</h2>

        <!-- Flash Message Area -->
        <div id="flash-message">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
        </div>

        <div class="row">
            <div class="col-lg-8">
                <h4>Order Summary</h4>
                <ul class="list-group" id="order-items">
                    @forelse ($cartItems as $cartItem)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                {{ $cartItem->product->product_name }}
                                @if ($cartItem->product->on_sale)
                                    <span class="badge bg-warning text-dark ms-2">On Sale</span>
                                @endif
                            </div>
                            <span>Quantity: {{ $cartItem->quantity }}</span>
                            <span
                                class="item-price">₱{{ number_format(($cartItem->product->on_sale ? $cartItem->product->sale_price : $cartItem->product->price) * $cartItem->quantity, 2) }}</span>
                        </li>
                    @empty
                        <li class="list-group-item text-center">The Cart is empty</li>
                    @endforelse
                </ul>

                <div class="mt-3">
                    <strong>Total: ₱<span id="order-total">{{ number_format($cartTotal, 2) }}</span></strong>
                </div>
            </div>

            <div class="col-lg-4">
                <h4>Customer Information</h4>
                <form action="{{ route('staff.pos.placeOrder') }}" method="POST" id="checkout-form">
                    @csrf
                    <div class="mb-3">
                        <label for="customer_name" class="form-label">Customer Name</label>
                        <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="table_number" class="form-label">Table Number</label>
                        <input type="number" class="form-control" id="table_number" name="table_number" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Method</label><br>
                        <input type="radio" id="payment_cash" name="payment_method" value="cash"
                            onchange="handlePaymentMethodChange()">
                        <label for="payment_cash">Cash</label><br>
                        <input type="radio" id="payment_gcash" name="payment_method" value="gcash"
                            onchange="handlePaymentMethodChange()">
                        <label for="payment_gcash">GCash</label><br>
                        <input type="radio" id="payment_paymaya" name="payment_method" value="paymaya"
                            onchange="handlePaymentMethodChange()">
                        <label for="payment_paymaya">PayMaya</label>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" class="form-control" id="amount" name="amount" disabled>
                        <div id="amount-error" class="text-danger" style="display: none;">Amount is required when paying
                            with cash.</div>
                    </div>
                    <button type="submit" class="btn btn-success">Place Order</button>
                </form>
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

        // Function to handle payment method change
        function handlePaymentMethodChange() {
            const cashInput = document.getElementById('amount');
            const cashCheckbox = document.getElementById('payment_cash');
            const gcashCheckbox = document.getElementById('payment_gcash');
            const paymayaCheckbox = document.getElementById('payment_paymaya');
            const amountError = document.getElementById('amount-error');

            if (cashCheckbox.checked) {
                cashInput.disabled = false; // Enable amount input for cash
                cashInput.required = true; // Make the amount field required
                amountError.style.display = "block"; // Show error message for cash
            } else {
                cashInput.disabled = true; // Disable amount input
                cashInput.required = false; // Make the amount field not required
                amountError.style.display = "none"; // Hide error message
            }

            // Disable input if GCash or PayMaya is selected
            if (gcashCheckbox.checked || paymayaCheckbox.checked) {
                cashInput.disabled = true;
                cashInput.required = false; // Make the amount field not required
                amountError.style.display = "none"; // Hide error message
            }
        }

        // AJAX Form Submission
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('checkout-form');

            form.addEventListener('submit', function(event) {
                const cashCheckbox = document.getElementById('payment_cash');
                const amountInput = document.getElementById('amount');

                // Show error if cash is selected and amount is not filled
                if (cashCheckbox.checked && amountInput.value === '') {
                    event.preventDefault(); // Prevent the form from submitting
                    alert('Amount is required when paying with cash.'); // Show alert
                    return; // Exit the function
                }

                event.preventDefault(); // Prevent the default form submission

                const formData = new FormData(form); // Create a FormData object

                // AJAX request
                fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token
                        },
                    })
                    .then(response => {
                        // Check if the response is OK
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json(); // Parse the JSON response
                    })
                    .then(data => {
                        if (data.message) {
                            // Handle success (e.g., show a success message, redirect)
                            alert(data.message); // Or use a flash message
                            // Redirect to the success page
                            window.location.href = '{{ route('staff.pos.order-success') }}';
                        } else {
                            // Handle error (e.g., show an error message)
                            alert('There was an error processing your order. Please try again.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('There was an error processing your order. Please try again.');
                    });
            });
        });
    </script>

@endsection
