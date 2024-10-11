@extends('layouts.staff.app') <!-- Adjust the layout as necessary -->

@section('title', 'Order Success')

@section('content')
    <div class="container">
        <h2>Order Successfully Placed!</h2>
        <p>Your order has been successfully placed. Thank you for your purchase!</p>
        <a href="{{ route('staff.pos.index') }}" class="btn btn-primary">New Order</a>
    </div>
@endsection
