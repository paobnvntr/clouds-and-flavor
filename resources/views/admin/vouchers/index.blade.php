@extends('layouts.admin.app')

@section('title', 'Admin | Vouchers')

@section('content')
<main class="app-main">
    <div class="app-content-header bg-light py-3">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 text-dark">Vouchers</h3>
                </div>
                <div class="col-sm-6 text-end">
                    <a href="{{ route('admin.vouchers.create') }}" class="btn btn-primary">Add Voucher</a>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="col-sm-12">
                    <div class="table-responsive shadow-sm bg-white p-3 rounded">
                        <table id="vouchersTable" class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">ID</th>
                                    <th class="text-start">Code</th>
                                    <th class="text-start">Discount</th>
                                    <th class="text-start">Type</th>
                                    <th class="text-center">Expiry Date</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Usage</th>
                                    <th class="text-center">Min. Purchase</th>
                                    <th class="text-center">Max Discount</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($vouchers as $voucher)
                                    <tr>
                                        <td class="text-center">{{ $voucher->id }}</td>
                                        <td class="text-start">{{ $voucher->code }}</td>
                                        <td class="text-start">{{ $voucher->discount }}</td>
                                        <td class="text-start">{{ ucfirst($voucher->type) }}</td>
                                        <td class="text-center">{{ $voucher->expiry_date }}</td>
                                        <td class="text-center">
                                            @if (\Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($voucher->expiry_date)))
                                                <span class="badge bg-danger">Expired</span>
                                            @elseif ($voucher->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-warning">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $voucher->times_used }} /
                                            {{ $voucher->usage_limit ?: 'Unlimited' }}</td>
                                        <td class="text-center">{{ $voucher->minimum_purchase ?: 'N/A' }}</td>
                                        <td class="text-center">{{ $voucher->max_discount ?: 'N/A' }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.vouchers.edit', $voucher->id) }}"
                                                class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('admin.vouchers.destroy', $voucher->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    @media screen and (max-width: 768px) {
        .btn-danger {
            margin-top: 10px;
        }
    }
</style>

<script>
    new DataTable('#vouchersTable', {
        lengthMenu: [5, 10, 20, 50, 100],
    });

    setTimeout(function () {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.style.transition = "opacity 0.5s ease";
            alert.style.opacity = 0;
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
</script>
@endsection