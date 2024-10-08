@extends('layouts.admin.app')

@section('title', 'Order List')

@section('content')

    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">All Order List</h3>
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

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User Name</th>
                                    <th>Total Items</th>
                                    <th>Total Price</th>
                                    <th>Status</th>
                                    <th>Payment Method</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->user->name ?? 'N/A' }}</td>
                                        <td>{{ $order->orderItems->sum('quantity') }}</td>
                                        <td>{{ $order->total_price }}</td>
                                        <td>{{ $order->status }}</td>
                                        <td>{{ $order->payment_method ?? 'N/A' }}</td>
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
                                                    <p><strong>Status:</strong>
                                                        {{ $order->status ?? 'N/A' }}
                                                    </p>
                                                    <p><strong>Payment Method:</strong>
                                                        {{ $order->payment_method ?? 'N/A' }}
                                                    </p>
                                                    <p><strong>Address:</strong>
                                                        {{ $order->address ?? 'N/A' }}
                                                    </p>
                                                    <p><strong>Phone Number:</strong>
                                                        {{ $order->phone_number ?? 'N/A' }}
                                                    </p>
                                                    <hr>
                                                    <ul>
                                                        <p><strong>Total Items:</strong>
                                                            {{ $order->orderItems->sum('quantity') }}
                                                        </p>
                                                        @php $totalAmount = 0; @endphp 
                                                        @foreach ($order->orderItems as $item)
                                                            @php
                                                                $itemTotal = $item->quantity * $item->price;
                                                                $totalAmount += $itemTotal; 
                                                            @endphp
                                                            <li>{{ $item->product->product_name ?? 'N/A' }}
                                                                ({{ $item->quantity }}) -
                                                                ₱{{ number_format($item->price, 2) }} =
                                                                ₱{{ number_format($itemTotal, 2) }}</li>
                                                            <!-- Displaying item total -->
                                                        @endforeach
                                                    </ul>
                                                    <hr>
                                                   
                                                    <p><strong>Total Amount:</strong> ₱{{ number_format($totalAmount, 2) }}
                                                    </p> <!-- Display total amount -->
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
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

@endsection
