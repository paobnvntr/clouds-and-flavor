@extends('layouts.admin.app')

@section('title', 'Admin | Category')

@section('content')
<main class="app-main">
    <div class="app-content-header bg-light py-3">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 text-dark">Categories</h3>
                </div>
                <div class="col-sm-6 text-end">
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">Add New Category</a>
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
                        <table id="categoriesTable" class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">ID</th>
                                    <th class="text-center">Image</th>
                                    <th class="text-start">Name</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $category)
                                    <tr>
                                        <td class="text-center">{{ $category->id }}</td>
                                        <td class="text-center">
                                            <img src="{{ asset($category->image ?? 'assets/category_image/unknown.jpg') }}"
                                                alt="{{ $category->name }}" class="img-thumbnail"
                                                style="width: 50px; height: 50px;">
                                        </td>
                                        <td class="text-start">{{ $category->name }}</td>
                                        <td class="text-center">
                                            <span class="badge {{ $category->status == 0 ? 'bg-success' : 'bg-danger' }}">
                                                {{ $category->status == 0 ? 'Available' : 'Unavailable' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.categories.edit', $category->id) }}"
                                                class="btn btn-warning btn-sm">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.categories.destroy', $category->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Are you sure you want to delete this category?')">
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
    new DataTable('#categoriesTable', {
        layout: {
            bottomEnd: {
                paging: {
                    firstLast: false
                }
            }
        },
        lengthMenu: [5, 10, 20, 50, 100],
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