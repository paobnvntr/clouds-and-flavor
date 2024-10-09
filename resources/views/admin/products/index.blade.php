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
                <!-- Add Product Button -->
                <div class="col-sm-12 mb-3">
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Add Product</a>
                </div>

                <table id="datatablesSimple" class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Sale</th>
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
                                    <img src="{{ asset($product->image) }}" alt="{{ $product->product_name }}" width="50">
                                </td>
                                <td>
                                    @if ($product->on_sale)
                                        <span class="badge bg-warning">Yes</span>
                                    @else
                                        <span class="badge bg-secondary">No</span>
                                    @endif
                                </td>
                                <td>{{ $product->product_name }}</td>
                                <td>₱{{ number_format($product->price, 2, '.', ',') }}</td>
                                <td>
                                    <input type="number" value="{{ $product->stock }}" class="stock-input form-control"
                                        data-product-id="{{ $product->id }}" style="width: 80px;">
                                </td>
                                <td>
                                    @if ($product->status == 0)
                                        <span class="badge bg-success">Available</span>
                                    @else
                                        <span class="badge bg-danger">Unavailable</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-warning">Edit</a>
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

<!-- Include jQuery for AJAX functionality -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        // Listen for changes in the stock input fields
        $('.stock-input').on('change', function() {
            var productId = $(this).data('product-id');
            var newStock = $(this).val();

            // AJAX request to update stock
            $.ajax({
                url: "{{ route('admin.products.update_stock') }}", // URL for stock update
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}', // Include CSRF token
                    product_id: productId,
                    stock: newStock
                },
                success: function(response) {
                    alert('Stock updated successfully');
                },
                error: function(xhr) {
                    alert('Error updating stock');
                }
            });
        });
    });
</script>
@endsection
