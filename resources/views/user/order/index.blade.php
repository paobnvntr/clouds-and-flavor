@extends('layouts.user.app')

@section('title', 'Your Orders')

@section('content')

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
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">My Orders</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">My Orders</li>
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
                    <strong><p id="orderAddress"></p></strong>
                    <h6>Phone Number:</h6>
                    <strong><p id="orderPhoneNumber"></p></strong>
                    <h6>Status:</h6>
                    <strong><p id="orderStatus"></p></strong>
                    <h6>Order Items:</h6>
                    <ul id="orderItemsList" class="list-group"></ul>
                    <h6>Total Price:</h6>
                    <strong><p id="orderTotalPrice"></p></strong> 
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
        $('#orderModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var order = button.data('order'); // Extract order data from data-* attributes

            // Log the order object to the console for debugging
            console.log(order);

            // Clear the existing items
            $('#orderItemsList').empty();

            // Check if order data exists
            if (order && order.order_items) { // Assuming 'order_items' is the correct name
                let totalOrderPrice = 0; // Initialize total order price

                order.order_items.forEach(item => {
                    const pricePerUnit = item.price; // Assuming `price` is the field for item price
                    const totalPrice = item.quantity * pricePerUnit; // Calculate total price for the item
                    totalOrderPrice += totalPrice; // Add to the total order price

                    $('#orderItemsList').append(
                        `<li class="list-group-item">
                            ${item.product.product_name} - (${item.quantity})
                            - Price/Unit: ₱${number_format(pricePerUnit, 2)} 
                            - Total: ₱${number_format(totalPrice, 2)}
                        </li>`
                    );
                });

                // Set the total price in the modal
                $('#orderTotalPrice').text(`₱${number_format(totalOrderPrice, 2)}`);
            }

            // Populate modal fields
            $('#orderAddress').text(order.address || 'N/A');
            $('#orderPhoneNumber').text(order.phone_number || 'N/A');
            $('#orderStatus').text(order.status || 'N/A');
        });

        // Function to format numbers as currency
        function number_format(number, decimals) {
            return Number(number).toLocaleString(undefined, { minimumFractionDigits: decimals, maximumFractionDigits: decimals });
        }
    </script>

@endsection
