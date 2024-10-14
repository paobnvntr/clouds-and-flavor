@extends('layouts.admin.app')

@section('title', 'Add Add-On')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Add New Add-ons</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('addons.index') }}">Add-ons</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add New Add-ons</li>
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
                            <h5 class="mb-0">Add-On Details</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('addons.store') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label for="name" class="form-label">Add-On Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="price" class="form-label">Price <span
                                            class="text-danger">*</span></label>
                                    <input type="number" name="price" step="0.01" class="form-control" required>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <button type="submit" class="btn btn-success">Create Add-On</button>
                                    <a href="{{ route('addons.index') }}" class="btn btn-danger">Cancel</a>
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