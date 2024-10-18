@extends('layouts.admin.app')

@section('title', 'Add Category')

@section('content')

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Add New Category</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Categories</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Category</li>
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
                            <h5 class="mb-0">Category Details</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.categories.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="mb-3">
                                    <label for="name" class="form-label">Category Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" required>
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

                                <div class="mb-3">
                                    <label for="image" class="form-label">Category Image</label>
                                    <input type="file" class="form-control" id="image" name="image" accept=".jpg, .jpeg, .png">
                                    <small class="form-text text-muted">If no image is uploaded, a default image will be
                                        used.</small>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <button type="submit" class="btn btn-success">Create Category</button>
                                    <a href="{{ route('admin.categories.index') }}" class="btn btn-danger">Cancel</a>
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