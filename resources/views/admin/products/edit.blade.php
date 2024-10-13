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
        @if (session('error'))
            <div class="alert alert-danger"
                style="position: fixed; top: 10%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;">
                {{ session('error') }}
            </div>
        @endif

        @if (session('message'))
            <div class="alert alert-success"
                style="position: fixed; top: 10%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;">
                {{ session('message') }}
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
            }, 3000); // 3000 milliseconds = 3 seconds
        </script>

        <div class="app-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <form action="{{ route('admin.products.update', $product->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            <div class="mb-3">
                                <label for="on_sale">On Sale</label>
                                <input type="checkbox" id="on_sale" name="on_sale" value="1"
                                    {{ $product->on_sale ? 'checked' : '' }} onclick="toggleSalePrice()">
                            </div>
                            <div class="mb-3">
                                <label for="sale_price">Sale Price</label>
                                <input type="number" step="0.01" id="sale_price" name="sale_price" class="form-control"
                                    value="{{ old('sale_price', $product->sale_price) }}"
                                    {{ $product->on_sale ? '' : 'disabled' }}>
                            </div>

                            <div class="mb-3">
                                <label for="product_name" class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="product_name" name="product_name"
                                    value="{{ $product->product_name }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Category</label>
                                <select class="form-control" id="category_id" name="category_id" required>
                                    @foreach ($categories as $category)
                                        @if ($category->status == 0)
                                            <!-- Show only available categories -->
                                            <option value="{{ $category->id }}"
                                                {{ $category->id == $product->category_id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
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
                                <small class="form-text text-muted">Hold down the Ctrl (Windows) or Command (Mac) button to
                                    select multiple options.</small>
                            </div>

                            <div class="mb-3">
                                <label for="price" class="form-label">Price</label>
                                <input type="number" step="0.01" class="form-control" id="price" name="price"
                                    value="{{ $product->price }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" required>{{ $product->description }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="stock" class="form-label">Stock</label>
                                <input type="number" class="form-control" id="stock" name="stock"
                                    value="{{ $product->stock }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="0" {{ $product->status == 0 ? 'selected' : '' }}>Available</option>
                                    <option value="1" {{ $product->status == 1 ? 'selected' : '' }}>Unavailable
                                    </option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Product Image</label>
                                <input type="file" class="form-control" id="image" name="image">
                                @if ($product->image)
                                    <img src="{{ asset($product->image) }}" alt="Product Image"
                                        class="img-thumbnail mt-2" width="150">
                                @else
                                    <img src="{{ asset('assets/product_image/unknown.jpg') }}" alt="Default Image"
                                        class="img-thumbnail mt-2" width="150">
                                @endif
                            </div>

                            <button type="submit" class="btn btn-primary">Update Product</button>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        function toggleSalePrice() {
            const salePriceInput = document.getElementById('sale_price');
            const onSaleCheckbox = document.getElementById('on_sale');
            salePriceInput.disabled = !onSaleCheckbox.checked;
            if (!onSaleCheckbox.checked) {
                salePriceInput.value = ''; // Clear sale price when checkbox is unchecked
            }
        }

        // Initialize the sale price input state based on the checkbox on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleSalePrice();
        });
    </script>

@endsection
