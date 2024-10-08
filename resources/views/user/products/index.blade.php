@extends('layouts.user.app')

@section('title', 'Products')

@section('content')

    <!--begin::App Main-->
    <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
            <!--begin::Container-->
            <div class="container-fluid">
                <!-- Flash message for success -->
                @if (session('success'))
                    <div class="alert alert-success" id="flash-message-content">
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
                <!--begin::Row-->
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Products</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Products
                            </li>
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
                    <!--begin::Col-->
                    <div class="col-lg-12 text-center">
                        <h2>Filter by Category</h2>
                        <div class="d-flex justify-content-center gap-2">
                            <button type="button" class="btn btn-primary"
                                onclick="window.location='{{ route('user.products.index') }}'">
                                All
                            </button>
                            @foreach ($categories as $category)
                                @if ($category->status == 0)
                                    <button type="button" class="btn btn-primary"
                                        onclick="window.location='{{ route('user.products-by-category', ['category' => $category->id]) }}'">
                                        {{ $category->name }}
                                    </button>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Row-->
            </div>
            <!--end::Container-->

            <section class="py-5">
                <div class="container-fluid">
                    <div class="row row-cols-2 row-cols-md-3 row-cols-xl-4">
                        @foreach ($products as $product)
                            <div class="col mb-5">
                                <div class="card h-100">
                                    <!-- Product Image -->
                                    <img src="{{ $product->image ? asset('/' . $product->image) : asset('/unknown.jpg') }}"
                                        class="card-img-top product-image" alt="{{ $product->product_name }}">

                                    <!-- Product Details -->
                                    <div class="card-body">
                                        <p class="card-title"><strong>{{ $product->product_name }}</strong></p>
                                        <p class="card-text text-end">₱{{ number_format($product->price, 2) }}</p>
                                        <hr>
                                        <p class="card-text">{{ Str::limit($product->description, 100) }}</p>
                                        <p class="card-text"><strong>Stock: <span
                                                    id="stock-{{ $product->id }}">{{ $product->stock }}</span></strong>
                                        </p>
                                        <!-- Display stock information -->
                                        <form id="add-to-cart-form-{{ $product->id }}">
                                            @csrf
                                            <button type="button" class="btn btn-success add-to-cart"
                                                data-product-id="{{ $product->id }}">Add to Cart</button>
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

        </div>
        <!--end::App Content-->
    </main>
    <!--end::App Main-->

    <style>
        .product-image {
            height: 200px;
            /* Adjust height as needed */
            width: 100%;
            object-fit: cover;
            /* Ensures the image covers the area */
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).on('click', '.add-to-cart', function() {
            var productId = $(this).data('product-id');
            var stockElement = $('#stock-' + productId); // Get the stock element

            $.ajax({
                url: "{{ route('user.cart.add-to-cart') }}",
                type: "POST",
                data: {
                    product_id: productId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        // Update stock display on success
                        var currentStock = parseInt(stockElement.text());
                        stockElement.text(currentStock - 1); // Decrement stock by 1
                        $('#flash-message-content').text(response.message).show();
                    }
                },
                error: function(xhr) {
                    console.log(xhr);
                }
            });
        });
    </script>

@endsection
