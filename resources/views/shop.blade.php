@extends('layouts.user.app')

@section('title', 'Clouds N Flavor | Shop')

@section('extra-links')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
@endsection

@section('content')
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
                            <a href="{{ url('/landing-page-shop') }}">All Products</a>
                        </li>
                        @foreach ($categories as $category)
                            <li>
                                <a href="{{ route('landing-page-shop', ['category_id' => $category->id]) }}">
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
                            <form action="{{ url('/landing-page-shop') }}" method="GET">
                                <input type="text" name="search" placeholder="Search products" />
                                <button type="submit" class="site-btn">SEARCH</button>
                            </form>
                        </div>
                    </div>

                    @if (Auth::check() && Auth::user()->role == '0')
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
                    @else
                        <div class="header__cart col-4">
                            <ul>
                                <li>
                                    <a href="{{ url('/my-cart') }}">
                                        <i class="fa fa-shopping-cart"></i>
                                        <span>0</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="header__cart__price">Total: <span>₱ 0.00</span></div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<section class="breadcrumb-section set-bg" data-setbg="{{ asset('assets/img/deviceseries.jpg') }}">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">  
                <div class="breadcrumb__text">
                    <h2>Shop</h2>
                    <div class="breadcrumb__option">
                        <a href="/">Home</a>
                        <span>Shop</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="product spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-5">
                <div class="sidebar">
                    <div class="sidebar__item">
                        <div class="latest-product__text">
                            <h4>Latest Products</h4>
                            <div class="latest-product__slider owl-carousel">
                                @foreach ($latestProducts->chunk(3) as $chunk)
                                    <div class="latest-prdouct__slider__item">
                                        @foreach ($chunk as $product)
                                            <a href="#" class="latest-product__item">
                                                <div class="latest-product__item__pic">
                                                    <img src="{{ asset('/' . $product->image) }}" alt="">
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
                                                    <form action="{{ route('user.cart.add-to-cart') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                        <input type="hidden" name="price"
                                                            value="{{ $product->sale_price }}">
                                                        <button type="submit" class="add-to-cart">
                                                            <i class="fa fa-shopping-cart"></i>
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="product__discount__item__text">
                                            <h5><a href="#">{{ $product->product_name }}</a></h5>
                                            <div class="product__item__price">
                                                ₱{{ number_format($product->sale_price, 2) }}
                                                <span>₱{{ number_format($product->price, 2) }}</span>
                                            </div>
                                            <p><strong>Stock:<span
                                                        id="stock-{{ $product->id }}">{{ $product->stock }}</span></strong>
                                            </p>

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
                                                <form action="{{ route('user.cart.add-to-cart') }}" method="POST"
                                                    id="add-to-cart-form-{{ $product->id }}">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                    <input type="hidden" name="price" value="{{ $product->price }}">
                                                    <button type="submit" class="add-to-cart"><i
                                                            class="fa fa-shopping-cart"></i></button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="product__item__text">
                                        <h6><a href="#">{{ $product->product_name }}</a></h6>
                                        <h5>₱{{ number_format($product->price, 2) }}</h5>
                                        <p><strong>Stock:<span
                                                    id="stock-{{ $product->id }}">{{ $product->stock }}</span></strong>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="d-flex align-items-center justify-content-between">
                        <p class="mb-0">Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of
                            {{ $products->total() }} results
                        </p>

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
    </div>
</section>
@endsection