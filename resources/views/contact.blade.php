@extends('layouts.user.app')

@section('title', 'Clouds N Flavor | Contact Us')

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
                    <div class="hero__search col-lg-8 col-md-12">
                        <div class="hero__search__form col-12">
                            <form action="{{ url('/landing-page-shop') }}" method="GET">
                                <input type="text" name="search" placeholder="Search products" />
                                <button type="submit" class="site-btn">SEARCH</button>
                            </form>
                        </div>
                    </div>

                    @if (Auth::check() && Auth::user()->role == '0')
                        <div class="header__cart col-lg-4 col-md-6">
                            <ul>
                                <li>
                                    <a href="{{ url('/my-cart') }}">
                                        <i class="fa fa-shopping-cart"></i>
                                        <span>{{ $cartItems }}</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="header__cart__price">Total: <span>₱ {{ number_format($totalPrice, 2) }}</span>
                            </div>
                        </div>
                    @else
                        <div class="header__cart col-lg-4 col-md-6">
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
                    <h2>Contact Us</h2>
                    <div class="breadcrumb__option">
                        @if (Auth::check() && Auth::user()->role == '0')
                            <a href="dashboard">Home</a>
                        @else
                            <a href="/">Home</a>
                        @endif

                        <span>Contact Us</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="contact spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-6 text-center">
                <div class="contact__widget">
                    <span class="icon_phone"></span>
                    <h4>Phone</h4>
                    <p>0906 587 2891</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 text-center">
                <div class="contact__widget">
                    <span class="icon_pin_alt"></span>
                    <h4>Address</h4>
                    <p>Blk 23 Acacia Cor Tanguile Street Calendola, San Pedro City, Laguna</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 text-center">
                <div class="contact__widget">
                    <span class="icon_clock_alt"></span>
                    <h4>Working Hours</h4>
                    <p>8:00 AM to 8:00 PM</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 text-center">
                <div class="contact__widget">
                    <span class="icon_mail_alt"></span>
                    <h4>Email</h4>
                    <p>cloudsnflavor@gmail.com</p>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="contact-form spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="contact__form__title">
                    <h2>Leave Message</h2>
                </div>
            </div>
        </div>
        <form action="{{ route('contact.store') }}" method="POST" id="contactUsForm">
            @csrf
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <input type="text" name="name" placeholder="Your name" required>
                </div>
                <div class="col-lg-6 col-md-6">
                    <input type="email" name="email" placeholder="Your Email" required>
                </div>
                <div class="col-lg-12 text-center">
                    <textarea name="message" placeholder="Your message" required></textarea>
                    <button type="submit" class="site-btn">SEND MESSAGE</button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    @media screen and (max-width: 768px) {
        .hero-normal {
            padding-bottom: 0 !important;
        }

        .product {
            padding-top: 30px !important;
        }
    }
</style>
@endsection