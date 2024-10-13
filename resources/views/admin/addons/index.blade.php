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
                        <h3 class="mb-0">Add-Ons Management</h3>
                    </div>

                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Add-ons</li>
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
                    <!-- Add Category Button -->
                    <div class="col-sm-12 mb-3">
                        <a href="{{ route('addons.create') }}" class="btn btn-primary">Add New Add-On</a>
                    </div>

                    <table id="datatablesSimple" class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($addOns as $addOn)
                                <tr>
                                    <td>{{ $addOn->id }}</td>
                                    <td>{{ $addOn->name }}</td>
                                    <td>₱{{ number_format($addOn->price, 2) }}</td>
                                    <td>
                                        <a href="{{ route('addons.edit', $addOn) }}" class="btn btn-warning">Edit</a>
                                        <form action="{{ route('addons.destroy', $addOn) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger"
                                                onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!--end::Row-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::App Content-->

    </main>
    <!--end::App Main-->

@endsection
