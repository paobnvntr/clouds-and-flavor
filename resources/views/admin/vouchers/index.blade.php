@extends('layouts.admin.app')

@section('title', 'Voucher Management')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <script>
                    // Function to hide alert after 3 seconds
                    setTimeout(function() {
                        const alerts = document.querySelectorAll('.alert');
                        alerts.forEach(alert => {
                            alert.style.transition = "opacity 0.5s ease";
                            alert.style.opacity = 0;
                            setTimeout(() => alert.remove(), 500);
                        });
                    }, 3000);
                </script>
                <div class="col-sm-6">
                    <h3 class="mb-0">Vouchers</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Vouchers</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 mb-3">
                    <a href="{{ route('admin.vouchers.create') }}" class="btn btn-primary">Add Voucher</a>
                </div>

                <table id="datatablesSimple" class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Code</th>
                            <th>Discount</th>
                            <th>Type</th>
                            <th>Expiry Date</th>
                            <th>Status</th>
                            <th>Usage</th>
                            <th>Min. Purchase</th>
                            <th>Max Discount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vouchers as $voucher)
                            <tr>
                                <td>{{ $voucher->id }}</td>
                                <td>{{ $voucher->code }}</td>
                                <td>{{ $voucher->discount }}</td>
                                <td>{{ ucfirst($voucher->type) }}</td>
                                <td>{{ $voucher->expiry_date }}</td>
                                <td>{{ $voucher->is_active ? 'Active' : 'Inactive' }}</td>
                                <td>{{ $voucher->times_used }} / {{ $voucher->usage_limit ?: 'Unlimited' }}</td>
                                <td>{{ $voucher->minimum_purchase ?: 'N/A' }}</td>
                                <td>{{ $voucher->max_discount ?: 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('admin.vouchers.edit', $voucher->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('admin.vouchers.destroy', $voucher->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
@endsection