@extends('layouts.admin.app')

@section('content')
    
<!--begin::App Main-->
<main class="app-main">
    <!--begin::App Content Header-->
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Edit Add-On</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('addons.index') }}">Add-ons</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Add-On</li>
                    </ol>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::App Content Header-->

    <!--begin::App Content-->
    <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-12">
                    <form action="{{ route('addons.update', $addOn->id) }}" method="POST">
                        @csrf
                        @method('PUT') <!-- This indicates a PUT request for updating -->
                
                        <div class="form-group">
                            <label for="name">Add-On Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $addOn->name) }}" required>
                        </div>
                
                        <div class="form-group">
                            <label for="price">Price</label>
                            <input type="number" name="price" step="0.01" class="form-control" value="{{ old('price', $addOn->price) }}" required>
                        </div>
                        <br>
                        <button type="submit" class="btn btn-primary">Update Add-On</button>
                        <a href="{{ route('addons.index') }}" class="btn btn-secondary">Back</a>
                    </form>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::App Content-->

</main>
<!--end::App Main-->
@endsection
