@extends('layouts.admin.app')

@section('title', 'Admin | Products')

@section('content')
<main class="app-main">
    <div class="app-content-header bg-light py-3">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 text-dark">Products</h3>
                </div>
                <div class="col-sm-6 text-end">
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Add New Product</a>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="alert alert-success" style="display:none;">
                    {{ session('success') }}
                </div>


                <div class="alert alert-danger" style="display:none;">
                    {{ session('error') }}
                </div>

                <div class="col-sm-12">
                    <div class="table-responsive shadow-sm bg-white p-3 rounded">
                        <table id="productsTable" class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">ID</th>
                                    <th class="text-center">Sale</th>
                                    <th class="text-center">Image</th>
                                    <th class="text-start">Name</th>
                                    <th class="text-center">Price</th>
                                    <th class="text-center">Stock</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Add-ons</th>
                                    <th class="text-center">Added On</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td class="text-center">{{ $product->id }}</td>
                                        <td class="text-center">
                                            @if ($product->on_sale)
                                                <span class="badge bg-success">Yes</span>
                                            @else
                                                <span class="badge bg-danger">No</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <img src="{{ asset($product->image ?? 'assets/product_image/unknown.jpg') }}"
                                                alt="{{ $product->name }}" class="img-thumbnail"
                                                style="width: 50px; height: 50px;">
                                        </td>
                                        <td class="text-start">{{ $product->product_name }}</td>
                                        <td class="text-center">₱{{ number_format($product->price, 2, '.', ',') }}</td>
                                        <td class="text-center">
                                            <input type="number" class="form-control stock-input"
                                                value="{{ $product->stock }}" data-product-id="{{ $product->id }}"
                                                value="{{ $product->stock }}" style="width: 80px;">
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="badge {{ $product->stock == 0 ? 'bg-danger' : 'bg-success' }} status-badge">
                                                {{ $product->stock == 0 ? 'Unavailable' : 'Available' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if ($product->addOns->isNotEmpty())
                                                <!-- Check if there are associated add-ons -->
                                                @foreach ($product->addOns as $addOn)
                                                    <div>
                                                        {{ $addOn->name }} - ₱{{ number_format($addOn->price, 2, '.', ',') }}
                                                    </div>
                                                @endforeach
                                            @else
                                                <span>No Add-ons</span>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $product->created_at->format('m-d-Y') }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.products.edit', $product->id) }}"
                                                class="btn btn-warning btn-sm">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Are you sure you want to delete this product?')">
                                                    Delete
                                                </button>
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

<script>
    new DataTable('#productsTable', {
        layout: {
            bottomEnd: {
                paging: {
                    firstLast: false
                }
            }
        },
        lengthMenu: [5, 10, 20, 50, 100],
    });

    $(document).ready(function () {
        $('.stock-input').on('change', function () {
            var productId = $(this).data('product-id');
            var newStock = $(this).val();
            var row = $(this).closest('tr');
            var statusBadge = row.find('.status-badge');

            $.ajax({
                url: "{{ route('admin.products.update_stock') }}",
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    product_id: productId,
                    stock: newStock
                },
                success: function (response) {
                    if (response.success) {
                        $('.alert-success').text(response.success).fadeIn().delay(3000)
                            .fadeOut();

                        // Update the status badge based on the new stock
                        if (newStock == 0) {
                            statusBadge.removeClass('bg-success').addClass('bg-danger')
                                .text('Unavailable');
                        } else {
                            statusBadge.removeClass('bg-danger').addClass('bg-success')
                                .text('Available');
                        }
                    }
                },
                error: function (xhr) {
                    alert('Error updating stock');
                }
            });
        });
    });
</script>
@endsection