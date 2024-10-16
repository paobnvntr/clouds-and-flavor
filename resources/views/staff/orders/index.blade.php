@extends('layouts.staff.app')

@section('title', 'staff | Online Orders')

@section('content')

    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Online Order</h3>
                    </div>
                    <div class="col-sm-6">

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

                        <div class="table-responsive shadow-sm bg-white p-3 rounded">
                            <table id="ordersTable" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>User Name</th>
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
                                            <td>{{ $order->user->name ?? 'N/A' }}</td>
                                            <td>{{ $order->orderItems->sum('quantity') }}</td>
                                            <td>₱{{ number_format($order->total_price, 2) }}</td>
                                            <td>{{ $order->status }}</td>
                                            <td>{{ $order->payment_method ?? 'N/A' }}</td>
                                            <td>{{ $order->created_at->format('Y-m-d H:i:s') }}</td>
                                            <td>
                                                <button class="btn btn-info" data-bs-toggle="modal"
                                                    data-bs-target="#orderModal{{ $order->id }}">
                                                    View
                                                </button>
                                            </td>
                                        </tr>

                                        <div class="modal fade" id="orderModal{{ $order->id }}" tabindex="-1"
                                            aria-labelledby="orderModalLabel{{ $order->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title" id="orderModalLabel{{ $order->id }}">
                                                            <i class="fas fa-receipt"></i> Order Details - Order For:
                                                            <strong>{{ $order->user->name }}</strong>
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body" id="printableArea{{ $order->id }}">
                                                        <!-- Modal body content starts here -->
                                                        <div class="row mb-3">
                                                            <div class="col-sm-6">
                                                                <p><strong>Date & Time:</strong>
                                                                    {{ $order->created_at->format('Y-m-d H:i:s') ?? 'N/A' }}
                                                                </p>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <p><strong>Status:</strong> <span
                                                                        class="badge bg-success">{{ $order->status ?? 'N/A' }}</span>
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-sm-6">
                                                                <p><strong>Payment Method:</strong>
                                                                    {{ $order->payment_method ?? 'N/A' }}</p>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <p><strong>Phone Number:</strong>
                                                                    {{ $order->phone_number ?? 'N/A' }}</p>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-sm-12">
                                                                <p><strong>Address:</strong> {{ $order->address ?? 'N/A' }}
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <hr class="my-3">

                                                        <p><strong>Total Items:</strong>
                                                            {{ $order->orderItems->sum('quantity') }}</p>
                                                        <ul class="list-group list-group-flush mb-3">
                                                            @php $totalAmount = 0; @endphp
                                                            @foreach ($order->orderItems as $item)
                                                                @php
                                                                    $itemTotal = $item->quantity * $item->price;
                                                                    $totalAmount += $itemTotal;
                                                                @endphp
                                                                <li
                                                                    class="list-group-item d-flex justify-content-between align-items-center">
                                                                    {{ $item->product->product_name ?? 'N/A' }}
                                                                    ({{ $item->quantity }})
                                                                    <span>₱{{ number_format($item->price, 2) }} =
                                                                        ₱{{ number_format($itemTotal, 2) }}</span>
                                                                </li>
                                                            @endforeach
                                                        </ul>

                                                        @if ($order->orderAddOns->isNotEmpty())
                                                            <hr>
                                                            <p><strong>Add-Ons:</strong></p>
                                                            <ul class="list-group list-group-flush mb-3">
                                                                @foreach ($order->orderAddOns as $addOn)
                                                                    <li
                                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                                        {{ $addOn->addOn->name }} ({{ $addOn->quantity }})
                                                                        <span>₱{{ number_format($addOn->price, 2) }}</span>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @endif

                                                        @if ($order->voucher_id)
                                                            <hr>
                                                            <p><strong>Applied Voucher:</strong>
                                                                {{ $order->voucher->code ?? 'N/A' }}</p>
                                                            <p><strong>Discount:</strong>
                                                                ₱{{ number_format($order->voucher->discount, 2) }}</p>
                                                        @endif

                                                        <div class="text-center py-3">
                                                            <strong>Total Amount:
                                                                ₱{{ number_format($order->total_price, 2) }}</strong>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        @if ($order->status === 'pending')
                                                            <form action="{{ route('staff.orders.online-complete', $order->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <button type="submit" class="btn btn-success">Complete
                                                                    Order</button>
                                                            </form>
                                                        @endif
                                                        @if ($order->status === 'completed')
                                                            <button type="button" class="btn btn-primary"
                                                                onclick="printInvoice({{ $order->id }})">Print
                                                                Invoice</button>
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
            // Get only the modal body content for printing
            var content = document.getElementById('printableArea' + orderId).innerHTML;

            // Open a new window
            var printWindow = window.open('', '', 'height=600,width=800');

            // Write the modal body content to the print window
            printWindow.document.write('<html><head><title>Invoice</title>');
            printWindow.document.write('<style>/* Add any print-specific styles here */</style>');
            printWindow.document.write('</head><body>');
            printWindow.document.write(content);
            printWindow.document.write('</body></html>');

            // Print the content
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

        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.transition = "opacity 0.5s ease";
                alert.style.opacity = 0;
                setTimeout(() => alert.remove(), 500);
            });
        }, 3000);
    </script>
@endsection
