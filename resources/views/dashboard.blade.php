@extends('layouts.user.app')

@section('title', 'User CNFVAPE')

@section('content')


    <!-- Hero Section Begin -->
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
                    <div class="hero__search">
                        <div class="hero__search__form">
                            <form action="{{ route('user.products.index') }}" method="GET">
                                <input type="text" name="search" placeholder="What do you need?" />
                                <button type="submit" class="site-btn">SEARCH</button>
                            </form>
                        </div>
                    </div>


                    <div class="hero__item set-bg" data-setbg="{{ asset('assets/img/deviceseries.jpg') }}">
                        <div class="hero__text">
                            <span>HATDOG</span>
                            <h2>Bili ka bip? <br />bili na tol</h2>
                            <p>sige na tol</p>
                            <a href="{{ url('/products') }}" class="primary-btn">SHOP NOW</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Hero Section End -->

    <!-- Categories Section Begin -->
    <section class="categories">
        <div class="container">
            <div class="row">
                <div class="categories__slider owl-carousel">
                    @foreach ($categories as $category)
                        <div class="col-lg-3">
                            <div class="categories__item set-bg"
                                data-setbg="{{ asset($category->image ? '/' . $category->image : 'assets/category_image/unknown.jpg') }}">
                                <h5><a href="#">{{ $category->name }}</a></h5>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </section>
    <!-- Categories Section End -->

    <!-- New Product Begin -->
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
                                <h6><a href="#">{{ $product->product_name }}</a></h6>
                                <h5>₱{{ number_format($product->price, 2) }}</h5>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- New Product End -->



    <style>
        .hero__categories {
            height: 515px;
            /* Set the height you want for the container */
            overflow-y: auto;
            /* Enable vertical scrolling */
            /* Optional: add a border for better visibility */
            padding: 10px;
            /* Optional: add some padding */
            position: relative;
            /* Ensures that the scrollbar appears inside */
        }

        .category-list {
            list-style: none;
            /* Remove default list styles */
            padding: 0;
            /* Remove padding */
            margin: 0;
            /* Remove margin */
        }

        .category-list li {
            padding: 5px 0;
            /* Add some spacing between items */
        }

        /* Optional: styling for scrollbar */
        .hero__categories::-webkit-scrollbar {
            width: 8px;
            /* Width of the scrollbar */
        }

        .hero__categories::-webkit-scrollbar-thumb {
            background-color: #888;
            /* Color of the scrollbar thumb */
            border-radius: 10px;
            /* Rounded corners for the scrollbar thumb */
        }

        .hero__categories::-webkit-scrollbar-thumb:hover {
            background-color: #555;
            /* Darker color on hover */
        }
    </style>

@endsection
