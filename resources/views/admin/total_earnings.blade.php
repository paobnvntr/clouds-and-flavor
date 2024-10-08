@extends('layouts.admin.app') 

@section('title', 'Total Sales')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Total Earnings</h1>

    <form method="GET" action="{{ route('admin.total_earnings') }}">
        <div class="row mb-4">
            <div class="col-lg-4">
                <label for="start_date" class="form-label">Start Date:</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $startDate }}">
            </div>
            <div class="col-lg-4">
                <label for="end_date" class="form-label">End Date:</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $endDate }}">
            </div>
            <div class="col-lg-4">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary form-control">Filter</button>
            </div>
        </div>
    </form>

    <div class="row">
        <div class="col-lg-12">
            <div class="card text-center">
                <div class="card-header">
                    Total Earnings Overview
                </div>
                <div class="card-body">
                    <h2 class="card-title text-success">₱{{ $formattedEarnings }}</h2> 
                    <p class="card-text">Total earnings from all orders from {{ $startDate }} to {{ $endDate }}.</p>
                </div>
                <div class="card-footer text-muted">
                    Last updated: {{ now()->format('F j, Y, g:i a') }} 
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
