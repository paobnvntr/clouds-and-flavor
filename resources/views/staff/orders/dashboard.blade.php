@extends('layouts.staff.app')

@section('title', 'Staff Order Dashboard')

@section('content')
    <div class="container">
        <div class="row">
            <!-- Online Pending Orders Card -->
            <div class="col-lg-4 my-3">
                <a href="{{ url('/staff/online-pending') }}" class="text-decoration-none text-dark">
                    <div class="card">
                        <div class="card-body">
                            <h4>Online Pending Orders</h4>
                            <p>{{ $OLpendingOrders }}</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- POS Pending Orders Card -->
            <div class="col-lg-4 my-3">
                <a href="{{ url('/staff/pos/pending-orders') }}" class="text-decoration-none text-dark">
                    <div class="card">
                        <div class="card-body">
                            <h4>POS Pending Orders</h4>
                            <p>{{ $POSpendingOrders }}</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Completed Orders Card -->
            <div class="col-lg-4 my-3">
                <a href="/staff/completed-orders" class="text-decoration-none text-dark">
                    <div class="card">
                        <div class="card-body">
                            <h4>All Completed Orders</h4>
                            <p>{{ $completedOrdersCount }}</p> <!-- Show 0 if not set -->
                        </div>
                    </div>
                </a>
            </div>


            <!-- Orders to Deliver and Pick-up Card -->
            <div class="col-lg-4 my-3">
                <a href="{{ url('/staff/deliver-or-pickup') }}" class="text-decoration-none text-dark">
                    <div class="card">
                        <div class="card-body">
                            <h4>Pending Orders</h4>
                            <h5>Orders to Deliver:</h5>
                            <p>{{ $toDeliverOrders }}</p>
                            <h5>Orders to Pickup:</h5>
                            <p>{{ $pickUpOrders }}</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Completed Orders to Deliver and Pick-up Card -->
            <div class="col-lg-4 my-3">
                <a href="{{ url('/staff/deliver-or-pickup-completed') }}" class="text-decoration-none text-dark">
                    <div class="card">
                        <div class="card-body">
                            <h4>Completed Order</h4>
                            <h5>Deliver Orders:</h5>
                            <p>{{ $toDeliverOrdersCount ?? 0 }}</p> <!-- Use null coalescing operator -->
                            <h5>Pick-up Orders:</h5>
                            <p>{{ $pickUpOrdersCount ?? 0 }}</p> <!-- Use null coalescing operator -->
                        </div>
                    </div>
                </a>
            </div>



        </div>
    </div>
@endsection
