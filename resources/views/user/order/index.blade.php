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
                    }, 3000); // 3000 milliseconds = 3 seconds
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
                                <th>Status</th>
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
                                    <td>{{ $order->status }}</td>
                                    <td>
                                        <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#orderModal"
                                            data-order="{{ json_encode($order) }}">View</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal -->
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

                // Clear previous order items
                var orderItemsList = modal.find('#orderItemsList');
                orderItemsList.empty(); // Clear previous items
                $.each(order.order_items, function(index, item) {
                    orderItemsList.append('<li class="list-group-item">' + item.product
                        .product_name + ' - x' + item.quantity + '</li>');
                });
            });
        });

        // Function to format numbers as currency
        function number_format(number, decimals) {
            return Number(number).toLocaleString(undefined, {
                minimumFractionDigits: decimals,
                maximumFractionDigits: decimals
            });
        }
    </script>

    <script>
        // Function to format numbers as currency
        function number_format(number, decimals) {
            return Number(number).toLocaleString(undefined, {
                minimumFractionDigits: decimals,
                maximumFractionDigits: decimals
            });
        }
    </script>

@endsection
