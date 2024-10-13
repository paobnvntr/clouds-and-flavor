@extends('layouts.user.app')

@section('title', 'Products')

@section('content')

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- jQuery UI -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>


    <!-- Hero Section Begin -->
    <section class="hero hero-normal">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="hero__categories">
                        <div class="hero__categories__all">
                            <i class="fa fa-bars"></i>
                            <span>Categories</span>
                        </div>
                        <ul>
                            <li>
                                <a href="{{ route('user.products.index') }}">All Products</a>
                            </li>
                            @foreach ($categories as $category)
                                <li>
                                    <a href="{{ route('user.products.index', ['category_id' => $category->id]) }}">
                                        {{ $category->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="col-lg-9">
                    <div class="hero__search">
                        <div class="hero__search__form">
                            <form action="{{ route('user.products.index') }}" method="GET">
                                <input type="text" name="search" placeholder="Search products"
                                    value="{{ request('search') }}">
                                <button type="submit" class="site-btn">SEARCH</button>
                            </form>
                        </div>

                        <div class="header__cart">
                            <ul>
                                <li>
                                    <a href="{{ url('/my-cart') }}">
                                        <i class="fa fa-shopping-cart"></i>
                                        <span>{{ $cartItems }}</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="header__cart__price">item: <span>₱{{ number_format($totalPrice, 2) }}</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Hero Section End -->

    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-section set-bg" data-setbg="{{ asset('assets/img/deviceseries.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>CNFVAPE Shop</h2>
                        <div class="breadcrumb__option">
                            <a href="dashboard">Home</a>
                            <span>Shop</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->


    @if (session('error'))
        <div class="alert alert-danger"
            style="position: fixed; top: 10%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;">
            {{ session('error') }}
        </div>
    @endif

    @if (session('message'))
        <div class="alert alert-success"
            style="position: fixed; top: 10%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;">
            {{ session('message') }}
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
        }, 3000); // 3000 milliseconds = 3 seconds
    </script>

    <!-- Product Section Begin -->
    <section class="product spad">

        <div class="container">
            <div class="row">

                <div class="col-lg-3 col-md-5">
                    <div class="sidebar">

                        <!-- Latest Products Section -->
                        <div class="sidebar__item">
                            <div class="latest-product__text">
                                <h4>Latest Products</h4>
                                <div class="latest-product__slider owl-carousel">
                                    @foreach ($latestProducts->chunk(3) as $chunk)
                                        <div class="latest-prdouct__slider__item">
                                            @foreach ($chunk as $product)
                                                <a href="{{ route('user.products.product-details', $product->id) }}"
                                                    class="latest-product__item">
                                                    <div class="latest-product__item__pic">
                                                        <img src="{{ asset('/' . $product->image) }}"
                                                            alt="{{ $product->product_name }}">
                                                    </div>
                                                    <div class="latest-product__item__text">
                                                        <h6>{{ $product->product_name }}</h6>
                                                        <span>₱{{ number_format($product->price, 2) }}</span>
                                                    </div>
                                                </a>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-lg-9 col-md-7">
                    <div class="product__discount">
                        <div class="section-title product__discount__title">
                            <h2>Sale Off</h2>
                        </div>
                        <div class="row">
                            <div class="product__discount__slider owl-carousel">
                                @foreach ($discountedProducts as $product)
                                    <div class="col-lg-4">
                                        <div class="product__discount__item">
                                            <div class="product__discount__item__pic set-bg"
                                                data-setbg="{{ asset('/' . ($product->image ?? 'unknown.jpg')) }}">
                                                @php
                                                    $discountPercentage = round(
                                                        (($product->price - $product->sale_price) / $product->price) *
                                                            100,
                                                    );
                                                @endphp
                                                <div class="product__discount__percent">-{{ $discountPercentage }}%</div>
                                                <ul class="product__item__pic__hover">
                                                    <li>
                                                        <!-- Change the button to a link that redirects to product details -->
                                                        <a href="{{ route('user.products.product-details', $product->id) }}"
                                                            class="add-to-cart">
                                                            <i class="fa fa-shopping-cart"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="product__discount__item__text">
                                                <h5><a
                                                        href="{{ route('user.products.product-details', $product->id) }}">{{ $product->product_name }}</a>
                                                </h5>
                                                <div class="product__item__price">
                                                    ₱{{ number_format($product->sale_price, 2) }}
                                                    <span>₱{{ number_format($product->price, 2) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="product__filter">
                        <div class="section-title">
                            <h4>All
                                {{ request('category_id') ? $categories->firstWhere('id', request('category_id'))->name : 'Products' }}
                                {{ request('search') ? ' - Search Results for "' . request('search') . '"' : '' }}
                            </h4>
                        </div>

                        <div class="row">
                            @foreach ($products as $product)
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="product__item">
                                        <div class="product__item__pic set-bg"
                                            data-setbg="{{ asset('/' . ($product->image ?? 'unknown.jpg')) }}">
                                            <ul class="product__item__pic__hover">
                                                <li>
                                                    <!-- Change the button to a link that redirects to product details -->
                                                    <a href="{{ route('user.products.product-details', $product->id) }}"
                                                        class="add-to-cart">
                                                        <i class="fa fa-shopping-cart"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="product__item__text">
                                            <h6><a
                                                    href="{{ route('user.products.product-details', $product->id) }}">{{ $product->product_name }}</a>
                                            </h6>
                                            <h5>₱{{ number_format($product->price, 2) }}</h5>
                                            <p><strong>Stock:<span
                                                        id="stock-{{ $product->id }}">{{ $product->stock }}</span></strong>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <nav aria-label="Page navigation example">
                            <ul class="pagination">
                                <li class="page-item {{ $products->previousPageUrl() ? '' : 'disabled' }}">
                                    <a class="page-link" href="{{ $products->previousPageUrl() }}"
                                        tabindex="-1">Previous</a>
                                </li>
                                @for ($i = 1; $i <= $products->lastPage(); $i++)
                                    <li class="page-item {{ $products->currentPage() == $i ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $products->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor
                                <li class="page-item {{ $products->nextPageUrl() ? '' : 'disabled' }}">
                                    <a class="page-link" href="{{ $products->nextPageUrl() }}">Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- Product Section End -->

    <script>
        document.querySelectorAll('.add-to-cart-form').forEach(form => {
            form.addEventListener('submit', async (event) => {
                event.preventDefault();
                const formData = new FormData(form);
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                if (response.ok) {
                    const result = await response.json();
                    // Update the cart item count and total price
                    document.querySelector('.header__cart__price span').innerText = result.totalItems;
                    // Show the flash message
                    const flashMessage = document.querySelector('.alert');
                    if (flashMessage) {
                        flashMessage.innerText = result.message;
                        flashMessage.style.display = 'block';
                        setTimeout(() => {
                            flashMessage.style.display = 'none';
                        }, 3000);
                    }
                }
            });
        });
    </script>


@endsection
