<header class="header">
    <div class="header__top">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="header__top__left">
                        <ul>
                            <li><i class="fa fa-envelope"></i>cloudsnflavor@gmail.com</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="header__top__right">
                        <div class="header__top__right__social">
                            <a href="https://www.facebook.com/profile.php?id=100078434664934" target="_blank"><i
                                    class="fa fa-facebook"></i></a>
                        </div>

                        @if (Auth::check() && Auth::user()->role == '0')
                            <div class="header__top__right__logout">
                                <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button"
                                            data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-user"></i>
                                            {{ Auth::user()->name }}</a>
                                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                            <li>
                                                <a class="dropdown-item" href="{{route('profile.edit')}}">Profile</a>
                                                </a>

                                                <form method="POST" action="{{ route('logout') }}">
                                                    @csrf

                                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                                  this.closest('form').submit();">
                                                        {{ __('Log Out') }}
                                                    </a>
                                                </form>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        @else
                            <div class="header__top__right__auth">
                                <a href="{{ url('/register') }}" style="margin-right: 10px;"><i class="fa fa-user-plus"></i>
                                    Register</a>
                            </div>
                            <div class="header__top__right__auth ">
                                <a href="{{ url('/login') }}"><i class="fa fa-user"></i> Login</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="header__logo">
                    @if (Auth::check() && Auth::user()->role == '0')
                        <a href="dashboard"><img src="{{ asset('assets/img/cnfhomepage.png') }}" alt="" /></a>
                    @else
                        <a href="{{ url('/') }}"><img src="{{ asset('assets/img/cnfhomepage.png') }}" alt="" /></a>
                    @endif
                </div>
            </div>

            <div class="col-lg-6">
                <nav class="header__menu">
                    <ul>
                        @if (Auth::check() && Auth::user()->role == '0')
                            <li><a href="{{ url('/dashboard') }}">Home</a></li>
                            <li><a href="{{ url('/products') }}">Shop</a></li>
                            <li><a href="{{ url('/my-order') }}">My Order</a></li>
                            <li><a href="{{ url('/contact') }}">Contact Us</a></li>
                        @else
                            <li><a href="{{ url('/') }}">Home</a></li>
                            <li><a href="{{ url('/landing-page-shop') }}">Shop</a></li>
                            <li><a href="{{ url('/contact') }}">Contact Us</a></li>
                        @endif
                    </ul>
                </nav>
            </div>

        </div>
        <div class="humberger__open">
            <i class="fa fa-bars"></i>
        </div>
    </div>
</header>

<!-- Humburger -->
<div class="humberger__menu__overlay"></div>
<div class="humberger__menu__wrapper">
    <div class="humberger__menu__logo">
        <a href="dashboard"><img src="{{ asset('assets/img/CNF.jpg') }}" alt=""
                style="width: 80px; height: auto;" /></a>
    </div>

    @if (Auth::check() && Auth::user()->role == '0')
        <div class="header__top__right__logout">
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false"><i class="fa fa-user"></i> {{ Auth::user()->name }}</a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li>
                            <a class="dropdown-item" href="{{route('profile.edit')}}">Profile</a>
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                                  this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </a>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    @else
        <div class="humberger__menu__widget">
            <div class="header__top__right__auth d-flex align-items-center">
                <a href="{{ url('/register') }}" style="margin-right: 20px;"><i class="fa fa-user-plus"></i> Register</a>
                <a href="{{ url('/login') }}"><i class="fa fa-user"></i> Login</a>
            </div>
        </div>
    @endif

    <nav class="humberger__menu__nav mobile-menu">
        <ul>
            @if (Auth::check() && Auth::user()->role == '0')
                <li><a href="{{ url('/dashboard') }}">Home</a></li>
                <li><a href="{{ url('/products') }}">Shop</a></li>
                <li><a href="{{ url('/my-order') }}">My Order</a></li>
                <li><a href="{{ url('/contact') }}">Contact Us</a></li>
            @else
                <li><a href="{{ url('/') }}">Home</a></li>
                <li><a href="{{ url('/landing-page-shop') }}">Shop</a></li>
                <li><a href="{{ url('/contact') }}">Contact Us</a></li>
            @endif
        </ul>
    </nav>

    <div id="mobile-menu-wrap"></div>
    <div class="header__top__right__social">
        <a href="https://www.facebook.com/profile.php?id=100078434664934" target="_blank"><i class="fa fa-facebook"
                style="margin-right: 2px;"></i> Facebook Page</a>
    </div>
    <div class="humberger__menu__contact">
        <ul>
            <li><i class="fa fa-envelope"></i> cloudsnflavor@gmail.com</li>
        </ul>
    </div>
</div>
<!-- Humburger -->