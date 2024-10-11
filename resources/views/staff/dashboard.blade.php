@extends('layouts.staff.app')

@section('title', 'Staff Dashboard')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h4>Total Orders</h4>
                    <p>{{ $totalOrders }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h4>Pending Orders</h4>
                    <p>{{ $pendingOrders }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h4>Completed Orders</h4>
                    <p>{{ $completedOrders }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
