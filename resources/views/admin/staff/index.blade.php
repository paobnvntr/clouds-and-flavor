@extends('layouts.admin.app')

@section('title', 'Staff List')

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
                    <script>
                        setTimeout(function() {
                            const alerts = document.querySelectorAll('.alert');
                            alerts.forEach(alert => {
                                alert.style.transition = "opacity 0.5s ease";
                                alert.style.opacity = 0;
                                setTimeout(() => alert.remove(), 500);
                            });
                        }, 3000);
                    </script>
                    <div class="col-sm-6">
                        <h3 class="mb-0">Staff List</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Staff List</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12 mb-3">
                        <a href="{{ route('admin.staff.create') }}" class="btn btn-primary">Add Staff</a>
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
                            @foreach ($staff as $member)
                                <tr>
                                    <td>{{ $member->id }}</td>
                                    <td>{{ $member->name }}</td>
                                    <td>{{ $member->email }}</td>
                                    <td>{{ $member->phone_number }}</td>
                                    <td>{{ $member->address }}</td>
                                    <td>
                                        <!-- Display the role of the user -->
                                        @if ($member->role == 1)
                                            <span class="badge bg-warning">Staff</span>
                                        @else
                                            <span class="badge bg-warning">Other Role</span>
                                        @endif
                                    </td>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.staff.edit', $member->id) }}"
                                            class="btn btn-warning">Edit</a>
                                        <form action="{{ route('admin.staff.destroy', $member->id) }}" method="POST"
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
