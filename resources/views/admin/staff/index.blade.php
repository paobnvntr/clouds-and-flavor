@extends('layouts.admin.app')

@section('title', 'Admin | Staff List')

@section('content')
<main class="app-main">
    <div class="app-content-header bg-light py-3">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 text-dark">Staff List</h3>
                </div>
                <div class="col-sm-6 text-end">
                    <a href="{{ route('admin.staff.create') }}" class="btn btn-primary">Add New Staff</a>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-sm-12">
                    <div class="table-responsive shadow-sm bg-white p-3 rounded">
                        <table id="staffTable" class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">ID</th>
                                    <th class="text-start">Name</th>
                                    <th class="text-start">Email</th>
                                    <th class="text-start">Phone Number</th>
                                    <th class="text-start">Address</th>
                                    <th class="text-center">Role</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($staff as $member)
                                    <tr>
                                        <td class="text-center">{{ $member->id }}</td>
                                        <td class="text-start">{{ $member->name }}</td>
                                        <td class="text-start">{{ $member->email }}</td>
                                        <td class="text-start">{{ $member->phone_number }}</td>
                                        <td class="text-start">{{ $member->address }}</td>
                                        <td class="text-center">
                                            <span class="badge {{ $member->role == 1 ? 'bg-warning' : 'bg-info' }}">
                                                {{ $member->role == 1 ? 'Staff' : 'Other Role' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.staff.edit', $member->id) }}"
                                                class="btn btn-warning btn-sm">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.staff.destroy', $member->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Are you sure you want to delete this staff member?')">
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
    $(document).ready(function () {
        $('#staffTable').DataTable({
            responsive: true,
            paging: true,
            searching: true,
            ordering: true,
            lengthMenu: [5, 10, 20, 50, 100],
        });
    });

    setTimeout(function () {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.style.transition = "opacity 0.5s ease";
            alert.style.opacity = 0;
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
</script>
@endsection