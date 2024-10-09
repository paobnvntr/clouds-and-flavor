<!-- Header Section Begin -->
<header class="header">
    <div class="header__top">
      <div class="container">
        <div class="row">
          <div class="col-lg-6 col-md-6">
            <div class="header__top__left">
              <ul>
                <li><i class="fa fa-envelope"></i> cnfvape.com</li>
              </ul>
            </div>
          </div>
          <div class="col-lg-6 col-md-6">
            <div class="header__top__right">
              <div class="header__top__right__social">
                <a href="#"><i class="fa fa-facebook"></i></a>
                <a href="#"><i class="fa fa-instagram"></i></a>
              </div>

              <div class="header__top__right__logout">
                <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                  <li class="nav-item dropdown">
                      <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-user"></i></a>
                      <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                          {{-- <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                          <li><hr class="dropdown-divider" /></li> --}}
                          <li>
                              <form method="POST" action="{{ route('logout') }}">
                                  @csrf
              
                                  <a class="dropdown-item" href="{{route('logout')}}"
                                          onclick="event.preventDefault();
                                                      this.closest('form').submit();">
                                      {{ __('Log Out') }}
                                  </a>
                              </form>
                          </li>
                      </ul>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!--begin::End Navbar Links-->
    

    <div class="container">
      <div class="row">
        <div class="col-lg-3">
          <div class="header__logo">
            <a href="dashboard"><img src="{{ asset('assets/img/cnfhomepage.png') }}" alt=""/></a>
          </div>
        </div>
        
        <div class="col-lg-6">
          <nav class="header__menu">
            <ul>
              <li class="active"><a href="dashboard">Home</a></li>
              <li><a href="{{ url('/products') }}">Shop</a></li>
              {{-- <li>
                <a href="#">Pages</a>
                <ul class="header__menu__dropdown">
                  <li><a href="./shop-details.html">Shop Details</a></li>
                  <li><a href="./shoping-cart.html">Shoping Cart</a></li>
                  <li><a href="./checkout.html">Check Out</a></li>
                  <li><a href="./blog-details.html">Blog Details</a></li>
                </ul>
              </li> --}}
              
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



  <!-- Humberger Begin -->
<div class="humberger__menu__overlay"></div>
<div class="humberger__menu__wrapper">
  <div class="humberger__menu__logo">
    <a href="dashboard"><img src="{{ asset('assets/img/CNF.jpg') }}" alt="" style="width: 80px; height: auto;" /></a>
  </div>

  <div class="humberger__menu__cart">
    <ul>
      {{-- <li>
        <a href="#"><i class="fa fa-heart"></i> <span>1</span></a>
      </li> --}}
      <li>
        <a href="{{ url('/my-cart') }}">
          <i class="fa fa-shopping-cart"></i> <span>7</span></a>
      </li>
      <li>
        <a href="{{ url('/my-order') }}">
          <i class="fa fa-shopping-bag"></i> <span>3</span></a>
      </li>
    </ul>
    <div class="header__cart__price">item: <span>₱150.00</span></div>
  </div>
  
  <div class="humberger__menu__widget">
    <div class="header__top__right__auth">
      <a href="#"><i class="fa fa-user"></i> Login</a>
    </div>
  </div>
  <nav class="humberger__menu__nav mobile-menu">
    <ul>
      <li class="active"><a href="{{ url('/dashboard') }}">Home</a></li>
      <li><a href="{{ url('/products') }}">Shop</a></li>
      {{-- <li>
        <a href="#">Pages</a>
        <ul class="header__menu__dropdown">
          <li><a href="./shop-details.html">Shop Details</a></li>
          <li><a href="./shoping-cart.html">Shoping Cart</a></li>
          <li><a href="./checkout.html">Check Out</a></li>
          <li><a href="./blog-details.html">Blog Details</a></li>
        </ul>
      </li> --}}
    </ul>
  </nav>
  <div id="mobile-menu-wrap"></div>
  <div class="header__top__right__social">
    <a href="#"><i class="fa fa-facebook"></i></a>
    <a href="#"><i class="fa fa-instagram"></i></a>

  </div>
  <div class="humberger__menu__contact">
    <ul>
      <li><i class="fa fa-envelope"></i> cnfvape.com</li>
    </ul>
  </div>
</div>
<!-- Humberger End -->