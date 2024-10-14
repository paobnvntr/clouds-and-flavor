@extends('layouts.admin.app')

@section('title', 'Edit Product')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Edit Product</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Product</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5 class="mb-0">Product Details</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')

                                <!-- On Sale Checkbox and Sale Price -->
                                <div class="mb-3">
                                    <input type="checkbox" id="on_sale" name="on_sale" value="1" {{ $product->on_sale ? 'checked' : '' }} onclick="toggleSalePrice()">
                                    <label for="on_sale">On Sale</label>
                                </div>
                                <div class="mb-3" style="display: none;" id="sale_price_container">
                                    <label for="sale_price">Sale Price</label>
                                    <input type="number" step="0.01" id="sale_price" name="sale_price" class="form-control"
                                        value="{{ old('sale_price', $product->sale_price) }}" {{ $product->on_sale ? '' : 'disabled' }}>
                                </div>

                                <!-- Product Name -->
                                <div class="mb-3">
                                    <label for="product_name" class="form-label">Product Name</label>
                                    <input type="text" class="form-control" id="product_name" name="product_name" 
                                        value="{{ old('product_name', $product->product_name) }}" required>
                                </div>

                                <!-- Category -->
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Category</label>
                                    <select class="form-select" id="category_id" name="category_id" required>
                                        @foreach ($categories as $category)
                                            @if ($category->status == 0)
                                                <option value="{{ $category->id }}" 
                                                    {{ $category->id == $product->category_id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Add-ons Selection -->
                                <div class="mb-3">
                                    <label for="addons" class="form-label">Select Add-ons</label>
                                    <select class="form-control" id="addons" name="addons[]" multiple>
                                        @foreach ($addons as $addOn)
                                            <option value="{{ $addOn->id }}" 
                                                {{ in_array($addOn->id, $product->addOns->pluck('id')->toArray()) ? 'selected' : '' }}>
                                                {{ $addOn->name }} - ${{ $addOn->price }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">Hold down Ctrl (Windows) or Command (Mac) to select multiple options.</small>
                                </div>

                                <!-- Price -->
                                <div class="mb-3">
                                    <label for="price" class="form-label">Price</label>
                                    <input type="number" step="0.01" class="form-control" id="price" name="price" 
                                        value="{{ old('price', $product->price) }}" required>
                                </div>

                                <!-- Description -->
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" required>{{ old('description', $product->description) }}</textarea>
                                </div>

                                <!-- Stock -->
                                <div class="mb-3">
                                    <label for="stock" class="form-label">Stock</label>
                                    <input type="number" class="form-control" id="stock" name="stock" 
                                        value="{{ old('stock', $product->stock) }}" required>
                                </div>

                                <!-- Status -->
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="0" {{ $product->status == 0 ? 'selected' : '' }}>Available</option>
                                        <option value="1" {{ $product->status == 1 ? 'selected' : '' }}>Unavailable</option>
                                    </select>
                                </div>

                                <!-- Product Image -->
                                <div class="mb-3">
                                    <label for="image" class="form-label">Product Image</label>
                                    <input type="file" class="form-control" id="image" name="image">
                                    @if ($product->image)
                                        <img src="{{ asset($product->image) }}" alt="Product Image" class="img-thumbnail mt-2" width="150">
                                    @else
                                        <img src="{{ asset('assets/product_image/unknown.jpg') }}" alt="Default Image" class="img-thumbnail mt-2" width="150">
                                    @endif
                                </div>

                                <div class="d-flex justify-content-between">
                                    <button type="submit" class="btn btn-warning">Update Product</button>
                                    <a href="{{ route('admin.products.index') }}" class="btn btn-danger">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Script to Enable/Disable Sale Price Field -->
<script>
    function toggleSalePrice() {
        const salePriceContainer = document.getElementById('sale_price_container');
        const onSaleCheckbox = document.getElementById('on_sale');

        if (onSaleCheckbox.checked) {
            salePriceContainer.style.display = 'block';
            document.getElementById('sale_price').removeAttribute('disabled');
        } else {
            salePriceContainer.style.display = 'none';
            document.getElementById('sale_price').setAttribute('disabled', 'disabled');
        }
    }

    // Initialize sale price field state on page load
    document.addEventListener('DOMContentLoaded', function () {
        toggleSalePrice();
    });
</script>

@endsection