@extends('layouts.staff.app')

@section('title', 'Staff | POS Completed Orders')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">POS Completed Order</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="table-responsive shadow-sm bg-white p-3 rounded">
                        <table id="ordersTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Customer Name</th>
                                    <th>Total Items</th>
                                    <th>Total Price</th>
                                    <th>Status</th>
                                    <th>Payment Method</th>
                                    <th>Date & Time</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->customer_name ?? 'N/A' }}</td>
                                        <td>{{ $order->orderItems->sum('quantity') }}</td>
                                        <td>₱{{ number_format($order->total_price, 2) }}</td>
                                        <td>{{ ucfirst($order->status) }}</td>
                                        <td>{{ $order->payment_method ?? 'N/A' }}</td>
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
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title" id="orderModalLabel{{ $order->id }}">
                                                        <i class="fas fa-receipt"></i> Order Details - Order For:
                                                        <strong>{{ $order->customer_name }}</strong>
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body" id="printableArea{{ $order->id }}">
                                                    <!-- Modal body content -->
                                                    <div class="row mb-3">
                                                        <div class="col-sm-6">
                                                            <p><strong>Table #:</strong> {{ $order->table_number ?? 'N/A' }}</p>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <p><strong>Date & Time:</strong> {{ $order->created_at->format('Y-m-d H:i:s') ?? 'N/A' }}</p>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <p><strong>Status:</strong> <span class="badge bg-success">{{ ucfirst($order->status) }}</span></p>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-sm-6">
                                                            <p><strong>Payment Method:</strong> {{ $order->payment_method ?? 'N/A' }}</p>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <p><strong>Amount:</strong> {{ $order->amount ?? 'N/A' }}</p>
                                                        </div>
                                                    </div>

                                                    <hr class="my-3">

                                                    <p><strong>Total Items:</strong> {{ $order->orderItems->sum('quantity') }}</p>
                                                    <ul class="list-group list-group-flush mb-3">
                                                        @php $totalAmount = 0; @endphp
                                                        @foreach ($order->orderItems as $item)
                                                            @php
                                                                $itemTotal = $item->quantity * $item->price;
                                                                $totalAmount += $itemTotal;
                                                            @endphp
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                {{ $item->product->product_name ?? 'N/A' }}
                                                                ({{ $item->quantity }})
                                                                <span>₱{{ number_format($item->price, 2) }} = ₱{{ number_format($itemTotal, 2) }}</span>
                                                            </li>
                                                        @endforeach
                                                    </ul>


                                                    <div class="text-center py-3">
                                                        <strong>Total Amount: ₱{{ number_format($totalAmount, 2) }}</strong>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    @if ($order->status === 'completed')
                                                        <button type="button" class="btn btn-primary" onclick="printInvoice({{ $order->id }})">Print Invoice</button>
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
    </div>
</main>

<script>
    function printInvoice(orderId) {
        var content = document.getElementById('printableArea' + orderId).innerHTML;
        var printWindow = window.open('', '', 'height=600,width=800');
        printWindow.document.write('<html><head><title>Invoice</title>');
        printWindow.document.write('<style>/* Add any print-specific styles here */</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write(content);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    }

    $(document).ready(function() {
        $('#ordersTable').DataTable({
            responsive: true,
            paging: true,
            searching: true,
            ordering: true,
        });
    });
</script>
@endsection
