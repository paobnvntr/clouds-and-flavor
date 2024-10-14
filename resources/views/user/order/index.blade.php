@extends('layouts.user.app')

@section('title', 'Clouds N Flavor | My Order')

@section('content')
<section class="hero hero-normal">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="hero__categories">
                    <div class="hero__categories__all">
                        <i class="fa fa-bars"></i>
                        <span>Categories</span>
                    </div>
                    <ul>
                        <li>
                            <a href="{{ route('user.products.index') }}">All Products</a>
                        </li>
                        @foreach ($categories as $category)
                            <li>
                                <a href="{{ route('user.products.index', ['category_id' => $category->id]) }}">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="row">
                    <div class="hero__search col-8">
                        <div class="hero__search__form col-12">
                            <form action="{{ route('user.products.index') }}" method="GET">
                                <input type="text" name="search" placeholder="Search products" />
                                <button type="submit" class="site-btn">SEARCH</button>
                            </form>
                        </div>
                    </div>

                    <div class="header__cart col-4">
                        <ul>
                            <li>
                                <a href="{{ url('/my-cart') }}">
                                    <i class="fa fa-shopping-cart"></i>
                                    <span>{{ $cartItems }}</span>
                                </a>
                            </li>
                        </ul>
                        <div class="header__cart__price">Total: <span>₱ {{ number_format($totalPrice, 2) }}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="breadcrumb-section set-bg" data-setbg="{{ asset('assets/img/deviceseries.jpg') }}">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="breadcrumb__text">
                    <h2>My Order</h2>
                    <div class="breadcrumb__option">
                        <a href="dashboard">Home</a>
                        <span>My Order</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@if (session('error'))
    <div class="alert alert-danger"
        style="position: fixed; top: 10%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;">
        {{ session('error') }}
    </div>
@endif

@if (session('message'))
    <div class="alert alert-success"
        style="position: fixed; top: 10%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;">
        {{ session('message') }}
    </div>
@endif

<main class="app-main">
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
                            <th>Delivery Option</th>
                            <th>Status</th>
                            <th>Payment Status</th>
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
                                <td>{{ ucfirst($order->delivery_option) }}</td>
                                <td>{{ $order->status }}</td>
                                <td>{{ ucfirst($order->payment_status) }}</td>
                                <td>
                                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#orderModal"
                                        data-order="{{ json_encode($order) }}">View</button>
                                    @if ($order->payment_status == 'unpaid')
                                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#paymentModal"
                                            data-order-id="{{ $order->id }}"
                                            data-payment-method="{{ $order->payment_method }}">Pay</button>
                                    @else
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold text-success" id="paymentModalLabel">Order Payment
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Payment Method -->
                <div class="mb-3 pb-2 border-bottom">
                    <h6 class="font-weight-bold">Payment Method:</h6>
                    <p id="paymentMethod" class="text-muted"></p>
                </div>

                <!-- QR Code Image -->
                <div class="text-center mb-3">
                    <img id="qrCodeImage" src="" alt="QR Code" class="img-fluid img-thumbnail" />
                </div>

                <!-- Reference Number Input -->
                <div class="mb-3">
                    <h6 class="font-weight-bold">Reference Number:</h6>
                    <input type="text" id="referenceNumber" class="form-control" placeholder="Enter Reference Number"
                        required>
                </div>

                <!-- Delivery Option -->
                <div class="form-group mb-3">
                    <h6 class="font-weight-bold">Delivery Option:</h6>
                    <select name="delivery_option" id="delivery_option" class="form-control" required>
                        <option value="" disabled selected>Select Delivery Option</option>
                        <option value="pick-up">Pick-up</option>
                        <option value="to-deliver">To Deliver</option>
                    </select>
                </div>

                <input type="hidden" id="orderId">
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="payNowBtn">Pay Now</button>
            </div>
        </div>
    </div>
</div>

<!-- Order Modal -->
<div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold text-info" id="orderModalLabel">Order Details</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Address Section -->
                <div class="mb-3 pb-2 border-bottom">
                    <h6 class="font-weight-bold">Address:</h6>
                    <p id="orderAddress" class="mb-0 text-muted"></p>
                </div>

                <!-- Phone Number Section -->
                <div class="mb-3 pb-2 border-bottom">
                    <h6 class="font-weight-bold">Phone Number:</h6>
                    <p id="orderPhoneNumber" class="mb-0 text-muted"></p>
                </div>

                <!-- Status Section -->
                <div class="mb-3 pb-2 border-bottom">
                    <h6 class="font-weight-bold">Status:</h6>
                    <p id="orderStatus" class="mb-0 text-muted"></p>
                </div>

                <!-- Order Items Section -->
                <div class="mb-3 pb-2 border-bottom">
                    <h6 class="font-weight-bold">Order Items:</h6>
                    <ul id="orderItemsList" class="list-group">
                        <!-- Order items go here -->
                    </ul>
                </div>

                <!-- Add-ons Section -->
                <div class="mb-3 pb-2 border-bottom">
                    <h6 class="font-weight-bold">Add-ons:</h6>
                    <ul id="orderAddonsList" class="list-group">
                        <!-- Add-ons go here -->
                    </ul>
                </div>

                <!-- Voucher Applied Section -->
                <div class="mb-3 pb-2 border-bottom">
                    <h6 class="font-weight-bold">Voucher Applied:</h6>
                    <p id="orderVoucher" class="mb-0 text-muted"></p>
                </div>

                <!-- Total Price Section -->
                <div class="mb-3">
                    <h6 class="font-weight-bold">Total Price:</h6>
                    <p id="orderTotalPrice" class="h5 font-weight-bold text-success"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    setTimeout(function () {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.style.transition = "opacity 0.5s ease";
            alert.style.opacity = 0;
            setTimeout(() => alert.remove(), 500);
        });
    }, 3000);

    $(document).ready(function () {
        $('#orderModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var order = button.data('order');

            var modal = $(this);
            modal.find('#orderAddress').text(order.address);
            modal.find('#orderPhoneNumber').text(order.phone_number);
            modal.find('#orderStatus').text(order.status);
            modal.find('#orderTotalPrice').text('₱' + number_format(order.total_price, 2));

            if (order.voucher) {
                modal.find('#orderVoucher').text(order.voucher.code + ' (₱' + number_format(order
                    .voucher.discount, 2) + ' off)');
            } else {
                modal.find('#orderVoucher').text('No voucher applied');
            }

            var orderItemsList = modal.find('#orderItemsList');
            orderItemsList.empty();

            $.each(order.order_items, function (index, item) {
                var price = item.product.on_sale ? item.product.sale_price : item.product.price;
                var priceText = item.product.on_sale ?
                    `<del>₱${number_format(item.product.price, 2)}</del> ₱${number_format(item.product.sale_price, 2)}` :
                    `₱${number_format(item.product.price, 2)}`;

                orderItemsList.append('<li class="list-group-item">' +
                    item.product.product_name +
                    ' - x' + item.quantity +
                    ' @ ' + priceText +
                    '</li>');
            });

            // Add-ons section
            var orderAddonsList = modal.find('#orderAddonsList');
            orderAddonsList.empty();

            if (order.addons && order.addons.length > 0) {
                $.each(order.addons, function (index, addon) {
                    orderAddonsList.append('<li class="list-group-item">' +
                        addon.name +
                        ' - ₱' + number_format(addon.price, 2) +
                        '</li>');
                });
            } else {
                orderAddonsList.append('<li class="list-group-item">No add-ons</li>');
            }
        });


        // Payment modal setup
        $('#paymentModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var paymentMethod = button.data('payment-method');
            var orderId = button.data('order-id');

            // Populate payment modal fields
            $('#paymentMethod').text(paymentMethod);
            $('#orderId').val(orderId);
            $('#payNowBtn').prop('disabled', true);
            $('#referenceNumber').val('');

            // Simulate fetching a QR code image based on the payment method
            $('#qrCodeImage').attr('src', '/assets/img/' + paymentMethod + '.jpg');
        });

        // Enable/disable Pay Now button based on Reference Number input
        $('#referenceNumber').on('input', function () {
            var referenceNumber = $(this).val();
            $('#payNowBtn').prop('disabled', !referenceNumber.trim());
        });

        // Complete payment action
        $('#payNowBtn').on('click', function () {
            var orderId = $('#orderId').val();
            var referenceNumber = $('#referenceNumber').val();
            var deliveryOption = $('#delivery_option').val();

            // Proceed with payment submission
            $.ajax({
                url: '/orders/pay',
                type: 'POST',
                data: {
                    order_id: orderId,
                    reference_number: referenceNumber,
                    delivery_option: deliveryOption,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    $('#paymentModal').modal('hide');
                    alert('Payment sent Successfully.');
                    location.reload(); // Refresh the page to see updated order status
                },
                error: function (xhr) {
                    // Handle error
                    alert('Payment could not be processed. Please try again.');
                }
            });
        });
    });

    function number_format(number, decimals) {
        return Number(number).toLocaleString('en-US', {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals
        });
    }
</script>
@endsection