@extends('layouts.admin.app')

@section('title', 'Add Product')

@section('content')

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Add Products</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Categories</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Product</li>
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
                            <form action="{{ route('admin.products.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="mb-3">
                                    <label for="product_name" class="form-label">Product Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="product_name" name="product_name"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Category <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="category_id" name="category_id" required>
                                        <option value="" disabled selected>--Select Category--</option>
                                        @foreach ($categories as $category)
                                            @if ($category->status == 0)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="price" class="form-label">Price <span
                                            class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control" id="price" name="price"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control" id="description" name="description"
                                        required></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="stock" class="form-label">Stock <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="stock" name="stock" required>
                                </div>

                                <div class="mb-3">
                                    <label for="image" class="form-label">Product Image</label>
                                    <input type="file" class="form-control" id="image" name="image">
                                    <small class="form-text text-muted">If no image is uploaded, a default image will be
                                        used.</small>
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Status <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="" disabled selected>--Select Status--</option>
                                        <option value="0">Available</option>
                                        <option value="1">Unavailable</option>
                                    </select>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <button type="submit" class="btn btn-success">Create Product</button>
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
@endsection