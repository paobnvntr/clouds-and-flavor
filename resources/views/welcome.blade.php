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
                    <a href="/login"><i class="fa fa-heart"></i> <span>0</span></a>
                </li>
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
                            <form action="{{ route('landing-page-shop') }}" method="GET">
                                <input type="text" name="search" placeholder="What do you need?" />
                                <button type="submit" class="site-btn">SEARCH</button>
                            </form>
                        </div>
                        <div class="header__cart">
                            <ul>
                                <li>
                                    <a href="/login"><i class="fa fa-heart"></i> <span>0</span></a>
                                </li>
                                <li>
                                    <a href="/login"><i class="fa fa-shopping-cart"></i> <span>0</span></a>
                                </li>
                            </ul>
                            <div class="header__cart__price">item: <span>₱0.00</span></div>
                        </div>
                    </div>

                    <div class="hero__item set-bg" data-setbg="{{ asset('assets/img/deviceseries.jpg') }}">
                        <div class="hero__text">
                            <span>HATDOG</span>
                            <h2>Bili ka bip? <br />bili na tol</h2>
                            <p>sige na tol</p>
                            <a href="{{ url('/landing-page-shop') }}" class="primary-btn">SHOP NOW</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section Begin -->
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
                                <h6><a href="/products">{{ $product->product_name }}</a></h6>
                                <h5>₱{{ number_format($product->price, 2) }}</h5>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- New Product End -->

    <!-- Banner Begin -->
    <div class="banner">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="banner__pic">
                        <img src="{{ asset('assets/img/cloudsand.jpg') }}" alt="" />
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="banner__pic">
                        <img src="{{ asset('assets/img/cnfhome.png') }}" alt="" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Banner End -->





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


</body>

</html>
