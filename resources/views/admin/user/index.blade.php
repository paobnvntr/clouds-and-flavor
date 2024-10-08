@extends('layouts.admin.app')

@section('title', 'Users')

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
                        <h3 class="mb-0">Users</h3>
                    </div>

                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Users</li>
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
                    <div class="col-sm-12 mb-3">
                        <a href="{{ route('admin.user.create') }}" class="btn btn-primary">Add User</a>
                    </div>
                    <table id="datatablesSimple" class="display">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th>Address</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone_number }}</td>
                                    <td>{{ $user->address }}</td>
                                    <td>
                                        <!-- Display the role of the user -->
                                        @if ($user->role == 0)
                                            <span class="badge bg-info">User</span>
                                        @else
                                            <span class="badge bg-warning">Other Role</span>
                                        @endif
                                    </td>
                                    
                                    <td>
                                        <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-warning">Edit</a>
                                        <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST"
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
                <!--end::Row-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::App Content-->

    </main>
    <!--end::App Main-->
    
    <script>
        $(document).ready(function() {
            $('#datatablesSimple').DataTable({
                responsive: true,
                paging: true,
                searching: true,
                ordering: true,
            });
        });
    </script>

@endsection
