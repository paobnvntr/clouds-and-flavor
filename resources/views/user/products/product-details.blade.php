@extends('layouts.user.app')

@section('title', 'Clouds N Flavor | Product Details')

@section('content')

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
    setTimeout(function () {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.style.transition = "opacity 0.5s ease"; // Add a fade effect
            alert.style.opacity = 0; // Fade out the alert
            setTimeout(() => alert.remove(), 500); // Remove after fade out
        });
    }, 3000); // 3000 milliseconds = 3 seconds
</script>

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
                <div class="row">
                    <div class="hero__search col-8">
                        <div class="hero__search__form col-12">
                            <form action="{{ route('user.products.index') }}" method="GET">
                                <input type="text" name="search" placeholder="Search products" />
                                <button type="submit" class="site-btn">SEARCH</button>
                            </form>
                        </div>
                    </div>

                    <div class="header__cart col-4">
                        <ul>
                            <li>
                                <a href="{{ url('/my-cart') }}">
                                    <i class="fa fa-shopping-cart"></i>
                                    <span>{{ $cartItems }}</span>
                                </a>
                            </li>
                        </ul>
                        <div class="header__cart__price">Total: <span>₱ {{ number_format($totalPrice, 2) }}</span></div>
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
                    <h2>Product Details</h2>
                    <div class="breadcrumb__option">
                        <a href="/dashboard">Home</a>
                        <a href="/products">Shop</a>
                        <span>{{ $product->product_name }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Breadcrumb Section End -->

<!-- Product Details Section Begin -->
<section class="product-details spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="product__details__pic">
                    <div class="product__details__pic__item">
                        <img class="product__details__pic__item--large" src="{{ asset('/' . $product->image) }}"
                            alt="{{ $product->name }}">
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="product__details__text">
                    <h3>{{ $product->product_name }}</h3>

                    @if ($product->on_sale)
                        <div class="product__details__price">
                            <span class="text-danger">On Sale!</span>
                            <br>
                            <span style="text-decoration: line-through;">₱{{ number_format($product->price, 2) }}</span>
                            <span class="text-success">₱{{ number_format($product->sale_price, 2) }}</span>
                        </div>
                    @else
                        <div class="product__details__price">₱{{ number_format($product->price, 2) }}</div>
                    @endif

                    <p></p>

                    <!-- Add to Cart Form -->
                    <div class="product__details__add_ons">
                        <form action="{{ route('user.cart.add-to-cart') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1"> <!-- Default quantity -->

                            <!-- If there are add-ons, display them -->
                            @if ($product->addOns->count() > 0)
                                <h6 style="margin-bottom: 10px;">Available Add-ons:</h6>
                                <div class="form-group">
                                    @foreach ($product->addOns as $addOn)
                                        <div class="form-check" style="margin-left: 20px;">
                                            <input type="checkbox" name="addons[]" value="{{ $addOn->id }}"
                                                class="form-check-input" id="addon-{{ $addOn->id }}">
                                            <label class="form-check-label" for="addon-{{ $addOn->id }}">
                                                {{ $addOn->name }} (₱ {{ number_format($addOn->price, 2) }})
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p>No add-ons available for this product.</p>
                            @endif

                            <button type="submit" class="primary-btn">ADD TO CART</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="product__details__tab">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab"
                                aria-selected="true">Product Description</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tabs-1" role="tabpanel">
                            <div class="product__details__tab__desc">
                                <p>{{ $product->description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
</section>
@endsection