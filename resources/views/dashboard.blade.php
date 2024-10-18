@extends('layouts.user.app')

@section('title', 'Clouds N Flavor | Home')

@section('content')
<section class="hero">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="hero__categories">
                    <div class="hero__categories__all">
                        <i class="fa fa-bars"></i>
                        <span>Categories</span>
                    </div>
                    <ul class="category-list">
                        @foreach ($categories as $category)
                            <li>
                                <p>
                                    {{ $category->name }}
                                </p>
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

                <div class="hero__item set-bg" data-setbg="{{ asset('assets/img/deviceseries.jpg') }}">
                    <div class="hero__overlay"></div>
                    <div class="hero__text">
                        <span>Enjoy Smoking</span>
                        <h2>Inhale the good stuff,<br />exhale the bad</h2>
                        <p>Shop Premium Vapes Now!</p>
                        <a href="{{ url('/products') }}" class="primary-btn">SHOP NOW</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="categories">
    <div class="container">
        <div class="row">
            <div class="categories__slider owl-carousel">
                @foreach ($categories as $category)
                    <div class="col-lg-3">
                        <div class="categories__item set-bg"
                            data-setbg="{{ asset($category->image ? '/' . $category->image : 'assets/category_image/unknown.jpg') }}">
                            <h5><a href="/products">{{ $category->name }}</a></h5>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</section>

<section class="featured spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title">
                    <h2>New Arrivals</h2>
                </div>

            </div>
        </div>
        <div class="row featured__filter">
            @foreach ($newProducts as $product)
                <div class="col-lg-3 col-md-4 col-sm-6 mix {{ strtolower($product->category) }}">
                    <div class="featured__item">
                        <div class="featured__item__pic set-bg" data-setbg="{{ asset('/' . $product->image) }}">
                        </div>
                        <div class="featured__item__text">
                            <h6>{{ $product->product_name }}</h6>
                            <h5>₱{{ number_format($product->price, 2) }}</h5>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="about-us spad">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="section-title">
                    <h2 style="font-size: 36px; font-weight: bold; color: #333;">About Us</h2>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="about-us-content text-center"
                    style="background: #fff; padding: 40px; border-radius: 10px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);">
                    <h3 style="font-size: 28px; font-weight: 700; color: #333;">WELCOME TO CLOUDS AND FLAVOR!</h3>
                    <p style="font-size: 16px; color: #555; margin-top: 20px;">
                        At Clouds and Flavor, we are passionate about providing high-quality vaping products and accessories to our
                        community. <br>
                        Our mission is to offer a safe, enjoyable, and informative experience for both
                        newcomers and seasoned vapers.
                    </p>
                    <p style="font-size: 16px; color: #555;">
                        With a wide selection of e-liquids, devices, and expert advice, we are here to help you find the
                        perfect fit. <br> 
                        Join us on your vaping journey and explore the latest trends and innovations in the
                        industry!
                    </p>
                   @if (Auth::check() && Auth::user()->role == '0')
                        <a href="{{ url('/products') }}" class="primary-btn">EXPLORE PRODUCTS</a>
                    @else
                        <a href="{{ url('/landing-page-shop') }}" class="primary-btn">EXPLORE PRODUCTS</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .hero__categories {
        height: 515px;
        overflow-y: auto;
        padding: 10px;
        position: relative;
    }

    .category-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .category-list li {
        padding: 5px 0;
    }

    .hero__categories::-webkit-scrollbar {
        width: 8px;
    }

    .hero__categories::-webkit-scrollbar-thumb {
        background-color: #888;
        border-radius: 10px;
    }

    .hero__categories::-webkit-scrollbar-thumb:hover {
        background-color: #555;
    }

    .hero__item {
        position: relative;
        background-size: cover;
        background-position: center;
    }

    .hero__overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1;
    }

    .hero__text {
        position: relative;
        z-index: 2;
    }
</style>
@endsection