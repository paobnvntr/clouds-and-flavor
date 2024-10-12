@extends('layouts.staff.app')

@section('title', 'Order List')

@section('content')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Pending Order List</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item active" aria-current="page">Orders</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <table id="datatablesSimple" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User Name</th>
                                    <th>Total Items</th>
                                    <th>Total Price</th>
                                    <th>Status</th>
                                    <th>Payment Method</th>
                                    <th>Payment Status</th>
                                    <th>Reference #</th>
                                    <th>Delivery Option</th>
                                    <th>Date & Time</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->user->name ?? 'N/A' }}</td>
                                        <td>{{ $order->orderItems->sum('quantity') }}</td>
                                        <td>₱{{ number_format($order->total_price, 2) }}</td>
                                        <td class="order-status">{{ $order->status }}</td>
                                        <td>{{ $order->payment_method ?? 'N/A' }}</td>
                                        <td>{{ $order->payment_status }}</td>
                                        <td>{{ $order->reference_number }}</td>
                                        <td>{{ $order->delivery_option }}</td>
                                        <td>{{ $order->created_at->format('Y-m-d H:i:s') }}</td>
                                        <td>
                                            <button class="btn btn-info" data-bs-toggle="modal"
                                                data-bs-target="#orderModal{{ $order->id }}">
                                                View
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Order Modal -->
                                    <div class="modal fade" id="orderModal{{ $order->id }}" tabindex="-1"
                                        aria-labelledby="orderModalLabel{{ $order->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="orderModalLabel{{ $order->id }}">Order
                                                        Details - Order For: <strong>{{ $order->user->name }}</strong></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p><strong>Order Date:</strong>
                                                        {{ $order->created_at->format('Y-m-d H:i:s') ?? 'N/A' }}</p>
                                                    <p><strong>Status:</strong> {{ $order->status ?? 'N/A' }}</p>
                                                    <p><strong>Payment Method:</strong>
                                                        {{ $order->payment_method ?? 'N/A' }}</p>
                                                    <p><strong>Address:</strong> {{ $order->address ?? 'N/A' }}</p>
                                                    <p><strong>Phone Number:</strong> {{ $order->phone_number ?? 'N/A' }}
                                                    </p>
                                                    <hr>

                                                    <p><strong>Total Items:</strong>
                                                        {{ $order->orderItems->sum('quantity') }}</p>
                                                    @php
                                                        $totalAmount = 0;
                                                        $discountAmount = 0;
                                                    @endphp

                                                    <ul>
                                                        @foreach ($order->orderItems as $item)
                                                            @php
                                                                // Check if the product is on sale
                                                                $itemPrice = $item->product->on_sale
                                                                    ? $item->product->sale_price
                                                                    : $item->price;
                                                                $itemTotal = $item->quantity * $itemPrice;
                                                                $totalAmount += $itemTotal;
                                                            @endphp
                                                            <li>
                                                                {{ $item->product->product_name ?? 'N/A' }}
                                                                ({{ $item->quantity }}) -
                                                                ₱{{ number_format($itemPrice, 2) }} =
                                                                ₱{{ number_format($itemTotal, 2) }}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                    <hr>

                                                    <!-- Vouchers Applied Section -->
                                                    <p><strong>Vouchers Applied:</strong>
                                                        @if ($order->voucher_id)
                                                            <!-- Check if a voucher_id exists -->
                                                            @php
                                                                $voucher = $order->voucher; // Fetch the voucher related to this order
                                                            @endphp
                                                            <ul>
                                                                <li>
                                                                    {{ $voucher->code }} -
                                                                    ₱{{ number_format($voucher->discount, 2) }}
                                                                    @php
                                                                        $discountAmount += $voucher->discount; // Add to total discount
                                                                    @endphp
                                                                </li>
                                                            </ul>
                                                        @else
                                                            None
                                                        @endif
                                                    </p>
                                                    <hr>

                                                    <p><strong>Total Amount Before Discount:</strong>
                                                        ₱{{ number_format($totalAmount, 2) }}</p>
                                                    <p><strong>Discount:</strong> ₱{{ number_format($discountAmount, 2) }}
                                                    </p>
                                                    <p><strong>Total Amount:</strong>
                                                        ₱{{ number_format($totalAmount - $discountAmount, 2) }}</p>
                                                    <!-- Display total amount after discount -->
                                                </div>  
                                                @if ($order->status != 'completed')
                                                    <button class="btn btn-success complete-order-btn"
                                                        data-id="{{ $order->id }}">
                                                        Complete Order
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        $(document).ready(function() {
            // Handle the click event on the Complete Order button
            $('.complete-order-btn').on('click', function() {
                var orderId = $(this).data('id');

                // Confirm completion
                if (confirm('Are you sure you want to mark this order as completed?')) {
                    $.ajax({
                        url: '{{ route('staff.orders.complete') }}', // URL to the route for completing the order
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}', // Include CSRF token
                            order_id: orderId
                        },
                        success: function(response) {
                            if (response.success) {
                                // Update the order status in the table
                                $('button[data-id="' + orderId + '"]').closest('tr').find(
                                    '.order-status').text('completed');

                                // Hide the Complete Order button
                                $('button[data-id="' + orderId + '"]').hide();

                                // Update modal status and close modal
                                $('#orderModal' + orderId).find('.modal-status').text(
                                    'completed');
                                $('#orderModal' + orderId).modal('hide');

                                alert('Order completed successfully!');
                            } else {
                                alert('Something went wrong!');
                            }
                        },
                        error: function() {
                            alert('Error completing the order.');
                        }
                    });
                }
            });

            // Refresh the table without a page reload after the modal is closed
            $('.modal').on('hidden.bs.modal', function() {
                location.reload();
            });
        });
    </script>

@endsection
