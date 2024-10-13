@extends('layouts.user.app')

@section('title', 'Your Orders')

@section('content')

    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-section set-bg" data-setbg="{{ asset('assets/img/deviceseries.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>My Order</h2>
                        <div class="breadcrumb__option">
                            <a href="dashboard">Home</a>
                            <span>Order</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
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
                    }, 3000);
                </script>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                <div class="row">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>Total Items</th>
                                <th>Total Price</th>
                                <th>Payment Method</th>
                                <th>Delivery Option</th>
                                <th>Status</th>
                                <th>Payment Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td>{{ $order->created_at }}</td>
                                    <td>{{ $order->orderItems->sum('quantity') }}</td> <!-- Total quantity of all items -->
                                    <td>₱{{ number_format($order->total_price, 2) }}</td>
                                    <td>{{ $order->payment_method }}</td>
                                    <td>{{ ucfirst($order->delivery_option) }}</td>
                                    <td>{{ $order->status }}</td>
                                    <td>{{ ucfirst($order->payment_status) }}</td>
                                    <td>
                                        <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#orderModal"
                                            data-order="{{ json_encode($order) }}">View</button>
                                        @if ($order->payment_status == 'unpaid')
                                            <button class="btn btn-success" data-bs-toggle="modal"
                                                data-bs-target="#paymentModal" data-order-id="{{ $order->id }}"
                                                data-payment-method="{{ $order->payment_method }}">Pay</button>
                                        @else
                                           
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Complete Payment (enter reference #)</h5>
                </div>
                <div class="modal-body">
                    <h6>Payment Method:</h6>
                    <strong>
                        <p id="paymentMethod"></p>
                    </strong>
                    <img id="qrCodeImage" src="" alt="QR Code" class="img-fluid mb-3" />

                    <h6>Reference Number:</h6>
                    <input type="text" id="referenceNumber" class="form-control" placeholder="Enter Reference Number"
                        required>

                    <div class="form-group">
                        <h6 for="delivery_option">Delivery Option:</h6>
                        <select name="delivery_option" id="delivery_option" class="form-control" required>
                            <option value="" disabled selected>Select Menu</option>
                            <option value="pick-up">Pick-up</option>
                            <option value="to-deliver">To Deliver</option>
                        </select>
                    </div>

                    <input type="hidden" id="orderId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="payNowBtn">Pay Now</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Modal -->
    <div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderModalLabel">Order Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6>Address:</h6>
                    <strong>
                        <p id="orderAddress"></p>
                    </strong>

                    <h6>Phone Number:</h6>
                    <strong>
                        <p id="orderPhoneNumber"></p>
                    </strong>

                    <h6>Status:</h6>
                    <strong>
                        <p id="orderStatus"></p>
                    </strong>

                    <h6>Order Items:</h6>
                    <ul id="orderItemsList" class="list-group"></ul>

                    <br>
                    <h6>Voucher Applied:</h6>
                    <strong>
                        <p id="orderVoucher"></p>
                    </strong>

                    <h6>Total Price:</h6>
                    <strong>
                        <p id="orderTotalPrice"></p>
                    </strong>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Include jQuery from a CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#orderModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var order = button.data('order'); // Extract info from data-* attributes

                // Populate the modal fields
                var modal = $(this);
                modal.find('#orderAddress').text(order.address);
                modal.find('#orderPhoneNumber').text(order.phone_number);
                modal.find('#orderStatus').text(order.status);
                modal.find('#orderTotalPrice').text('₱' + number_format(order.total_price, 2));

                // Voucher logic
                if (order.voucher) {
                    modal.find('#orderVoucher').text(order.voucher.code + ' (₱' + number_format(order.voucher.discount, 2) + ' off)');
                } else {
                    modal.find('#orderVoucher').text('No voucher applied');
                }

                // Clear previous order items
                var orderItemsList = modal.find('#orderItemsList');
                orderItemsList.empty(); // Clear previous items
                $.each(order.order_items, function(index, item) {
                    orderItemsList.append('<li class="list-group-item">' + item.product.product_name + ' - x' + item.quantity + ' @ ₱' + number_format(item.product.price, 2) + '</li>');
                });
            });

            // Payment modal setup
            $('#paymentModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var paymentMethod = button.data('payment-method');
                var orderId = button.data('order-id');

                // Populate payment modal fields
                $('#paymentMethod').text(paymentMethod);
                $('#orderId').val(orderId);
                $('#payNowBtn').prop('disabled', true);
                $('#referenceNumber').val('');

                // Simulate fetching a QR code image based on the payment method
                $('#qrCodeImage').attr('src', '/assets/img/' + paymentMethod + '.jfif');
            });

            // Enable/disable Pay Now button based on Reference Number input
            $('#referenceNumber').on('input', function() {
                var referenceNumber = $(this).val();
                $('#payNowBtn').prop('disabled', !referenceNumber.trim());
            });

            // Complete payment action
            $('#payNowBtn').on('click', function() {
                var orderId = $('#orderId').val();
                var referenceNumber = $('#referenceNumber').val();
                var deliveryOption = $('#delivery_option').val();

                // Proceed with payment submission
                $.ajax({
                    url: '/orders/pay', 
                    type: 'POST',
                    data: {
                        order_id: orderId,
                        reference_number: referenceNumber,
                        delivery_option: deliveryOption,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#paymentModal').modal('hide');
                        alert('Payment sent Successfully.');
                        location.reload(); // Refresh the page to see updated order status
                    },
                    error: function(xhr) {
                        // Handle error
                        alert('Payment could not be processed. Please try again.');
                    }
                });
            });
        });

        function number_format(number, decimals) {
            return Number(number).toLocaleString('en-US', {
                minimumFractionDigits: decimals,
                maximumFractionDigits: decimals
            });
        }
    </script>
@endsection
