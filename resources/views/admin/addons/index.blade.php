@extends('layouts.admin.app')

@section('title', 'Admin | Add-Ons')

@section('content')
<main class="app-main">
    <div class="app-content-header bg-light py-3">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 text-dark">Add-Ons Management</h3>
                </div>
                <div class="col-sm-6 text-end">
                    <a href="{{ route('addons.create') }}" class="btn btn-primary">Add New Add-On</a>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="col-sm-12">
                    <div class="table-responsive shadow-sm bg-white p-3 rounded">
                        <table id="addonsTable" class="table table-hover align-middle">
                            <thead class="table-light">
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
                                            <a href="{{ route('addons.edit', $addOn) }}"
                                                class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('addons.destroy', $addOn) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Are you sure you want to delete this add-on?')">Delete</button>
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

<style>
    @media screen and (max-width: 768px) {
        .btn-danger {
            margin-top: 10px;
        }
    }
</style>

<script>
    setTimeout(function () {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.style.transition = "opacity 0.5s ease";
            alert.style.opacity = 0;
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);

    new DataTable('#addonsTable', {
        layout: {
            bottomEnd: {
                paging: {
                    firstLast: false
                }
            }
        },
        perPage: 5,
        lengthMenu: [5, 10, 20, 50, 100],
    });
</script>
@endsection