@extends('layouts.staff.app')

@section('title', 'Pending Order List')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">POS Pending Order List</h3>
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
                                <th>Customer Name</th>
                                <th>Total Items</th>
                                <th>Total Price</th>
                                <th>Status</th>
                                <th>Payment Method</th>
                                <th>Amount</th>
                                <th>Date & Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->customer_name ?? 'N/A' }}</td>
                                    <td>{{ $order->items ? $order->items->sum('quantity') : 0 }}</td>
                                    <td>₱{{ number_format($order->total_price, 2) }}</td>
                                    <td class="order-status">{{ $order->status }}</td>
                                    <td>{{ $order->payment_method ?? 'N/A' }}</td>
                                    <td>{{ $order->amount ?? '0' }}</td>
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
                                                    Details - Order For: <strong>{{ $order->customer_name }}</strong></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>{{ $order->created_at->format('Y-m-d H:i:s') ?? 'N/A' }}</strong></p>
                                                <p><strong>Status:</strong> <span class="modal-status">{{ $order->status ?? 'N/A' }}</span></p>
                                                <p><strong>Payment Method:</strong>
                                                    {{ $order->payment_method ?? 'N/A' }}</p>
                                                <hr>
                                                <ul>
                                                    <p><strong>Total Items:</strong>
                                                        {{ $order->items ? $order->items->sum('quantity') : 0 }}</p>
                                                    @php $totalAmount = 0; @endphp
                                                    @foreach ($order->items as $item)
                                                        @php
                                                            $itemTotal = $item->quantity * $item->price;
                                                            $totalAmount += $itemTotal;
                                                        @endphp
                                                        <li>{{ $item->product->product_name ?? 'N/A' }}
                                                            ({{ $item->quantity }}) -
                                                            ₱{{ number_format($item->price, 2) }} =
                                                            ₱{{ number_format($itemTotal, 2) }}</li>
                                                    @endforeach
                                                </ul>
                                                <hr>
                                                <p><strong>Total Amount:</strong> ₱{{ number_format($totalAmount, 2) }}</p>
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
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Handle the click event on the Complete Order button
        $('.complete-order-btn').on('click', function() {
            var orderId = $(this).data('id');

            // Confirm completion
            if (confirm('Are you sure you want to mark this order as completed?')) {
                $.ajax({
                    url: '{{ route('staff.orders.pos-complete') }}', // Adjust this route as necessary
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}', // Include CSRF token
                        order_id: orderId
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update the order status in the table
                            $('button[data-id="' + orderId + '"]').closest('tr').find('.order-status').text('completed');

                            // Hide the Complete Order button
                            $('button[data-id="' + orderId + '"]').hide();

                            // Update modal status and close modal
                            $('#orderModal' + orderId).find('.modal-status').text('completed');
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
