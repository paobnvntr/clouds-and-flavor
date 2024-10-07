@extends('layouts.admin.app')

@section('title', 'Products')

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
                        // Function to hide alert after 5 seconds
                        setTimeout(function() {
                            const alerts = document.querySelectorAll('.alert');
                            alerts.forEach(alert => {
                                alert.style.transition = "opacity 0.5s ease"; // Add a fade effect
                                alert.style.opacity = 0; // Fade out the alert
                                setTimeout(() => alert.remove(), 500); // Remove after fade out
                            });
                        }, 3000); // 5000 milliseconds = 5 seconds
                    </script>

                    <div class="col-sm-6">
                        <h3 class="mb-0">Products</h3>
                    </div>

                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Products</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                <div class="row">
                    <!-- Add Category Button -->
                    <div class="col-sm-12 mb-3">
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Add Product</a>
                    </div>

                    <table id="datatablesSimple" class="display">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td>
                                        <img src="{{ asset($product->image) }}" alt="{{ $product->product_name }}"
                                            width="50">
                                    </td>
                                    <td>{{ $product->product_name }}</td>
                                    <td>₱{{ number_format($product->price, 2, '.', ',') }}</td>
                                    <td>{{ $product->stock }}</td>
                                    <td>
                                        <!-- Check status and display "Available" or "Unavailable" -->
                                        @if ($product->status == 0)
                                            <span class="badge bg-success">Available</span>
                                        @else
                                            <span class="badge bg-danger">Unavailable</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.products.edit', $product->id) }}"
                                            class="btn btn-warning">Edit</a>
                                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Delete</button>
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
    
    <!-- Include DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

    <!-- Include jQuery (required for DataTables) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    {{-- <script>
        $(document).ready(function() {
            $('#datatablesSimple').DataTable({
                responsive: true,
                paging: true,
                searching: true,
                ordering: true,
            });
        });
    </script> --}}

@endsection
