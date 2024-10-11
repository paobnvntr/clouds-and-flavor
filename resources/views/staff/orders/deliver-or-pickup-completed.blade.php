@extends('layouts.staff.app')

@section('title', 'Completed Order List')

@section('content')

    <div class="container">
        <h2>Completed:</h2>
        <!-- Completed Delivery Orders Section -->
        <h4>:To Delivery Order</h4>
        <table id="completedDeliveryOrders" class="table table-hover">
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
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($toDeliverOrdersCount as $order)
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
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#orderModal"
                                onclick="showOrderDetails({{ $order->id }}, 'user')">View</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Completed Pickup Orders Section -->
        <h4>:To Pickup Orders</h4>
        <table id="completedPickupOrders" class="table table-hover">
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
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pickUpOrdersCount as $order)
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
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#orderModal"
                                onclick="showOrderDetails({{ $order->id }}, 'user')">View</button>
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
                    <p><strong>Total Price:</strong> ₱${(Number(order.total_price) || 0).toFixed(2)}</p>
                    <p><strong>Status:</strong> ${order.status}</p>
                    <p><strong>Delivery Option:</strong> ${order.delivery_option}</p>
                    <h5>Order Items</h5>
                    <ul class="list-group">`;

                    if (order.order_items) {
                        order.order_items.forEach(item => {
                            const productName = item.product ? item.product.product_name : 'Unknown Product';
                            orderDetailsHtml += `
                            <li class="list-group-item">
                                ${productName} (₱${(Number(item.price) || 0).toFixed(2)} x ${item.quantity}) = ₱${((Number(item.price) || 0) * item.quantity).toFixed(2)}
                            </li>`;
                        });
                    }

                    if (order.items) {
                        order.items.forEach(item => {
                            orderDetailsHtml += `
                            <li class="list-group-item">
                                ${item.product_name} (₱${(Number(item.price) || 0).toFixed(2)} x ${item.quantity}) = ₱${((Number(item.price) || 0) * item.quantity).toFixed(2)}
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
@endsection
