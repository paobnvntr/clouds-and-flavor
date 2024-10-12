@extends('layouts.staff.app')

@section('title', 'Order List')

@section('content')

    <div class="container">
        <h2>Pending:</h2>

        <!-- Delivery Orders Section -->
        <h4>:To Deliver Orders</h4>
        <table id="userDeliveryOrders" class="table table-hover">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Total Items</th>
                    <th>Total Price</th>
                    <th>Payment Method</th>
                    <th>Reference #</th>
                    <th>Delivery Option</th>
                    <th>Status</th>
                    <th>Vouchers</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($deliveryOrders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->user->name }}</td>
                        <td>{{ $order->order_items_count }}</td>
                        <td>₱{{ number_format($order->total_price, 2) }}</td>
                        <td>{{ ucfirst($order->payment_method) }}</td>
                        <td>{{ ucfirst($order->reference_number) }}</td>
                        <td>{{ ucfirst($order->delivery_option) }}</td>
                        <td>{{ ucfirst($order->status) }}</td>
                        <td>
                            @if ($order->voucher_id)
                                @php
                                    $voucher = $order->voucher;
                                @endphp

                                {{ $voucher->code }} - ₱{{ number_format($voucher->discount, 2) }}
                            @else
                                None
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#orderModal"
                                onclick="showOrderDetails({{ $order->id }}, 'user')">View</button>
                            <button class="btn btn-success btn-sm" onclick="completeOrder({{ $order->id }})">Complete
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>




        <!-- Pickup Orders Section -->
        <h4>:To Pickup Orders</h4>
        <table id="userPickupOrders" class="table table-hover">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Total Items</th>
                    <th>Total Price</th>
                    <th>Payment Method</th>
                    <th>Reference #</th>
                    <th>Delivery Option</th>
                    <th>Status</th>
                    <th>Vouchers</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pickupOrders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->user->name }}</td>
                        <td>{{ $order->order_items_count }}</td>
                        <td>₱{{ number_format($order->total_price, 2) }}</td>
                        <td>{{ ucfirst($order->payment_method) }}</td>
                        <td>{{ ucfirst($order->reference_number) }}</td>
                        <td>{{ ucfirst($order->delivery_option) }}</td>
                        <td>{{ ucfirst($order->status) }}</td>
                        <td>
                            @if ($order->voucher_id)
                                @php
                                    $voucher = $order->voucher;
                                @endphp

                                {{ $voucher->code }} - ₱{{ number_format($voucher->discount, 2) }}
                            @else
                                None
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#orderModal"
                                onclick="showOrderDetails({{ $order->id }}, 'user')">View</button>
                            <button class="btn btn-success btn-sm" onclick="completeOrder({{ $order->id }})">Complete
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Modal for Viewing Order Details -->
        <div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="orderModalLabel">Order Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="orderDetailsContent">
                            <div class="text-center">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Helper function to format numbers with commas and two decimal places
        function formatCurrency(value) {
            return '₱' + Number(value).toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function showOrderDetails(orderId, orderType) {
            const orderDetailsContent = document.getElementById('orderDetailsContent');
            orderDetailsContent.innerHTML = `<div class="text-center">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>`;

            fetch(`/staff/orders/${orderType}/${orderId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    const order = data.order;

                    if (!order) {
                        throw new Error('Order data is missing');
                    }

                    let orderDetailsHtml = `
                        <h5>Customer Details</h5>
                        <p><strong>Name:</strong> ${order.customer_name || (order.user ? order.user.name : 'N/A')}</p>
                        <p><strong>Address:</strong> ${order.address || 'N/A'}</p>
                        <p><strong>Phone Number:</strong> ${order.phone_number || 'N/A'}</p>
                        <p><strong>Payment Method:</strong> ${order.payment_method}</p>
                        <p><strong>Status:</strong> ${order.status}</p>
                        <p><strong>Delivery Option:</strong> ${order.delivery_option}</p>
                        <p><strong>Total Price:</strong> ${formatCurrency(order.total_price)}</p>
                        <h5>Order Items</h5>
                        <ul class="list-group">`;

                    // Loop through order items to display accurately
                    if (order.order_items) {
                        order.order_items.forEach(item => {
                            const productName = item.product ? item.product.product_name : 'Unknown Product';
                            // Determine price based on sale status
                            const itemPrice = item.product && item.product.on_sale ?
                                item.product.sale_price : item.price;

                            orderDetailsHtml += `
                                <li class="list-group-item">
                                    ${productName} (${formatCurrency(itemPrice)} x ${item.quantity}) = ${formatCurrency((itemPrice * item.quantity))}
                                </li>`;
                        });
                    }

                    if (order.items) {
                        order.items.forEach(item => {
                            const itemPrice = item.on_sale ? item.sale_price : item.price; // Check if on sale
                            orderDetailsHtml += `
                                <li class="list-group-item">
                                    ${item.product_name} (${formatCurrency(itemPrice)} x ${item.quantity}) = ${formatCurrency((itemPrice * item.quantity))}
                                </li>`;
                        });
                    }

                    orderDetailsHtml += `</ul>`;
                    orderDetailsContent.innerHTML = orderDetailsHtml;
                })
                .catch(error => {
                    orderDetailsContent.innerHTML =
                        `<div class="alert alert-danger" role="alert">Error: ${error.message}</div>`;
                });
        }
    </script>


    <script>
        function completeOrder(orderId) {
            if (confirm('Are you sure you want to complete this order?')) {
                fetch(`/staff/orders/complete/${orderId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}' // Ensure CSRF token is included
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            alert('Order completed successfully!');
                            location.reload(); // Refresh the page to show updated order list
                        } else {
                            alert('Failed to complete order: ' + data.message);
                        }
                    })
                    .catch(error => {
                        alert('Error: ' + error.message);
                    });
            }
        }
    </script>
@endsection
