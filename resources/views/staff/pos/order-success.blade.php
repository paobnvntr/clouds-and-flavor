@extends('layouts.staff.app')

@section('title', 'Order Success')

@section('content')
<div class="container my-2" id="printableArea">
    <h2>Order Successfully Placed!</h2>
    <p class="text-muted">Your order has been successfully placed. Thank you for your purchase!</p>

    <h4>Order Summary</h4>
    <ul class="list-group" id="order-summary">
        @foreach ($order->orderItems as $item)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div class="d-flex justify-content-between w-100">
                    <div class="me-auto"> <!-- Product name and quantity -->
                        {{ $item->product->product_name }}
                        @if ($item->product->on_sale)
                            <!-- Check if product is on sale -->
                            <span class="badge bg-danger text-white ms-2">On Sale</span>
                            <span
                                class="text-muted text-decoration-line-through">₱{{ number_format($item->product->price, 2) }}</span>
                        @endif
                        <span class="badge bg-warning text-dark ms-2">x{{ $item->quantity }}</span>
                    </div>
                    <div class="text-end"> <!-- Align price elements -->
                        <span class="me-2">₱{{ number_format($item->price, 2) }}</span>
                        <!-- Individual price -->
                        <span class="item-price">₱{{ number_format($item->price * $item->quantity, 2) }}</span>
                        <!-- Total price -->
                    </div>
                </div>
            </li>
        @endforeach
    </ul>

    <div class="d-flex justify-content-end">
        <h4 class="mt-3">Total: ₱{{ number_format($order->total_price, 2) }}</h4>
    </div>

    <div class="d-flex justify-content-end mt-3">
        <button onclick="window.print()" class="btn btn-success me-2">Print Receipt</button>
        <a href="{{ route('staff.pos.index') }}" class="btn btn-primary">New Order</a>
    </div>
</div>

<style>
    @media print {
        body * {
            display: none;
            /* Hide all content */
        }

        #printableArea,
        #printableArea * {
            display: block;
            /* Show only the order summary */
        }

        #printableArea {
            position: relative;
            /* Changed from static to relative */
            left: 0;
            /* No horizontal offset */
            top: 0;
            /* Position the order summary at the top */
            width: 100%;
            /* Full width */
            margin: 0;
            /* No margin */
            padding: 20px;
            /* Add padding for spacing */
            font-family: Arial, sans-serif;
            /* Use a clean, professional font */
            border: 1px solid #ccc;
            /* Optional: border for definition */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            /* Optional: subtle shadow for depth */
        }

        h2,
        h4 {
            text-align: center;
            /* Center align headings */
            margin: 10px 0;
            /* Add vertical margin */
        }

        .list-group-item {
            display: flex;
            /* Flex for alignment */
            justify-content: space-between;
            /* Space between items */
            align-items: center;
            /* Center vertically */
            padding: 10px 15px;
            /* Add padding */
            border: 1px solid #eee;
            /* Light border for items */
            margin-bottom: 5px;
            /* Space between list items */
        }

        .item-price {
            font-weight: bold;
            /* Make total price bold */
            color: #28a745;
            /* Change total price color to green */
        }

        /* Align quantity to the right of the product name */
        .list-group-item div {
            flex: 1;
            /* Allow div to take available space */
            display: flex;
            /* Enable flex layout */
            justify-content: space-between;
            /* Space between product name and quantity */
            align-items: center;
            /* Center items vertically */
        }

        .list-group-item span.badge {
            margin-left: 10px;
            /* Space between product name and quantity */
        }

        #printableArea button,
        /* Hide the Print Receipt button */
        #printableArea a {
            /* Hide the New Order button */
            display: none;
        }
    }

    .item-price {
        font-weight: bold;
        /* Make total price bold */
        color: #28a745;
        /* Change total price color to green */
    }
</style>
@endsection