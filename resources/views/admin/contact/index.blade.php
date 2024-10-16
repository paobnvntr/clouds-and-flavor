@extends('layouts.admin.app')

@section('title', 'Admin | Contact Us Message')

@section('content')
<main class="app-main">
    <div class="app-content-header bg-light py-3">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 text-dark">Contact Us Messages</h3>
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

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="col-sm-12">
                    <div class="table-responsive shadow-sm bg-white p-3 rounded">
                        <table id="messagesTable" class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">ID</th>
                                    <th class="text-start">Name</th>
                                    <th class="text-start">Email</th>
                                    <th class="text-start">Message</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($messages as $message)
                                    <tr>
                                        <td class="text-center">{{ $message->id }}</td>
                                        <td class="text-start">{{ $message->name }}</td>
                                        <td class="text-start">{{ $message->email }}</td>
                                        <td class="text-start">{{ $message->message }}</td>
                                        <td class="text-center">
                                            <form action="{{ route('admin.messages.destroy', $message->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
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
    new DataTable('#messagesTable', {
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
