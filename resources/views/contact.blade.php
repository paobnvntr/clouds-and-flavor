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
                                <div class="header__cart__price">Total: <span>₱ {{ number_format($totalPrice, 2) }}</span>
                                </div>
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

    <!-- Breadcrumb Section Begin -->
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
    <!-- Breadcrumb Section End -->

    <!-- Contact Section Begin -->
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
                        <p>60-49 Road 11378 New York</p>
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

    @if (session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif
    <div class="contact-form spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="contact__form__title">
                        <h2>Leave Message</h2>
                    </div>
                </div>
            </div>
            <form action="{{ route('contact.store') }}" method="POST">
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
