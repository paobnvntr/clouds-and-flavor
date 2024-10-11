<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8" />
    <meta name="description" content="Ogani Template" />
    <meta name="keywords" content="Ogani, unica, creative, html" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title> CNFVAPE </title>

    <!-- jQuery (if needed) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;900&display=swap"
        rel="stylesheet" />
    <!-- Css Styles -->
    <link rel="stylesheet" href="{{ asset('assets/import/css/bootstrap.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/import/css/font-awesome.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/import/css/elegant-icons.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/import/css/nice-select.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/import/css/jquery-ui.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/import/css/owl.carousel.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/import/css/slicknav.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/import/css/style.css') }}" type="text/css">
</head>

<body>



    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Humberger Begin -->
    <div class="humberger__menu__overlay"></div>
    <div class="humberger__menu__wrapper">
        <div class="humberger__menu__logo">
            <a href="#"><img src="img/logo.png" alt="" /></a>
        </div>
        <div class="humberger__menu__cart">
            <ul>
                <li>
                    <a href="/login"><i class="fa fa-shopping-bag"></i> <span>0</span></a>
                </li>
            </ul>
            <div class="header__cart__price">item: <span>₱0.00</span></div>
        </div>

        <nav class="humberger__menu__nav mobile-menu">
            <ul>
                <li class="active"><a href="/">Home</a></li>
                <li><a href="/landing-page-shop">Shop</a></li>
                <li><a href="/contact">Contact</a></li>
            </ul>
        </nav>
        <div id="mobile-menu-wrap"></div>
        <div class="header__top__right__social">
            <a href="#"><i class="fa fa-facebook"></i></a>
            <a href="#"><i class="fa fa-instagram"></i></a>
        </div>
        <div class="humberger__menu__contact">
            <ul>
                <li><i class="fa fa-envelope"></i> cnfvape@gmail.com</li>
            </ul>
        </div>
    </div>
    <!-- Humberger End -->

    <!-- Header Section Begin -->
    <header class="header">
        <div class="header__top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <div class="header__top__left">
                            <ul>
                                <li><i class="fa fa-envelope"></i> cnfvape@gmail.com</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="header__top__right">
                            <div class="header__top__right__social">
                                <a href="#"><i class="fa fa-facebook"></i></a>
                                <a href="#"><i class="fa fa-instagram"></i></a>
                            </div>
                            <div class="header__top__right__auth">
                                <a href="/register"><i class="fa fa-user"></i> Register</a>
                            </div>
                            <div class="header__top__right__auth ">
                                <a href="/login"><i class="fa fa-user"></i> Login</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="header__logo">
                        <a href="/"><img src="{{ asset('assets/img/cnfhomepage.png') }}" alt="" /></a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <nav class="header__menu">
                        <ul>
                            <li class="active"><a href="/">Home</a></li>
                            <li><a href="/landing-page-shop">Shop</a></li>
                            <li><a href="/contact">Contact</a></li>
                        </ul>
                    </nav>
                </div>

            </div>
            <div class="humberger__open">
                <i class="fa fa-bars"></i>
            </div>
        </div>
    </header>
    <!-- Header Section End -->

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
                                <a href="{{ route('landing-page-shop') }}">All Products</a>
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
                    <div class="hero__search">
                        <div class="hero__search__form">
                            <form action="{{ route('landing-page-shop') }}" method="GET">
                                <input type="text" name="search" placeholder="Search products"
                                    value="{{ request('search') }}">
                                <button type="submit" class="site-btn">SEARCH</button>
                            </form>
                        </div>

                        <div class="header__cart">
                            <ul>
                                <li>
                                    <a href="/login"><i class="fa fa-shopping-cart"></i> <span>0</span></a>
                                </li>
                            </ul>
                            <div class="header__cart__price">item: <span>₱0.00</span></div>
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
                                                <div class="product__discount__percent">-{{ $discountPercentage }}%
                                                </div>
                                                <ul class="product__item__pic__hover">
                                                    <li>
                                                        <form action="{{ route('user.cart.add-to-cart') }}"
                                                            method="POST">
                                                            @csrf
                                                            <input type="hidden" name="product_id"
                                                                value="{{ $product->id }}">
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


                    {{-- Product section --}}
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
                                                    <form action="{{ route('user.cart.add-to-cart') }}"
                                                        method="POST" id="add-to-cart-form-{{ $product->id }}">
                                                        @csrf
                                                        <input type="hidden" name="product_id"
                                                            value="{{ $product->id }}">
                                                        <input type="hidden" name="price"
                                                            value="{{ $product->price }}">
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

    

    <!-- Footer Section Begin -->
    <footer class="footer spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="footer__about">
                        <div class="footer__about__logo">
                            <a href="dashboard"><img src="{{ asset('assets/img/CNF.jpg') }}" alt="" /></a>
                        </div>
                        <ul>
                            <li>Address: Address Sample blk 2</li>
                            <li>Phone: +63 </li>
                            <li>Email: example@domain.com</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 offset-lg-1">
                    <div class="footer__widget">
                        <h6>Useful Links</h6>
                        <ul>
                            <li><a href="{{ url('dashboard') }}">Home</a></li>
                            <li><a href="{{ url('/landing-page-shop') }}">Shop</a></li>
                            <li><a href="{{ url('/my-cart') }}">Cart</a></li>
                            <li><a href="{{ url('/my-order') }}">Order</a></li>
                        </ul>

                    </div>
                </div>
                <div class="col-lg-4 col-md-12">
                    <div class="footer__widget">
                        <div class="footer__widget__social">
                            <a href="#"><i class="fa fa-facebook"></i></a>
                            <a href="#"><i class="fa fa-instagram"></i></a>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="footer__copyright">
                            <div class="footer__copyright__text">
                                <p>
                                    Copyright &copy;
                                    <script>
                                        document.write(new Date().getFullYear());
                                    </script>
                                    All rights reserved |
                                    <a href="#" target="_blank">cnfvape</a>
                                </p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
    </footer>
    <!-- Footer Section End -->
    

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



    <!-- Bootstrap JS (Bundle with Popper.js included) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>

    <!-- Js Plugins -->
    <script src="{{ asset('assets/import/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('assets/import/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/import/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('assets/import/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/import/js/jquery.slicknav.js') }}"></script>
    <script src="{{ asset('assets/import/js/mixitup.min.js') }}"></script>
    <script src="{{ asset('assets/import/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/import/js/main.js') }}"></script>

</body>
