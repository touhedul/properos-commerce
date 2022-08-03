<!-- HEADER -->
<header>
    <?php /* <div class="tt-preloader"></div> */ ?>
    <div class="tt-header tt-header--build-01 tt-header--style-01 tt-header--sticky">
        <div id="show-announcement" style="width:100%;position:fixed;top:0;z-index:999;"></div>
        <div id="menu-header" class="tt-header__content">
            <div class="tt-header__logo colorize-theme13-bg">
                <div class="h1 tt-logo">
                    <a href="/">
                        <img src="/images/logo.png" alt="logo">
                    </a>
                </div>
            </div>
            <div class="tt-header__nav ">
                <div class="tt-header__menu">
                    <nav class="TonyM TonyM--header" data-tm-dir="row" data-tm-mob="true" data-tm-anm="emersion">
                        <ul class="TonyM__panel">
                            <li>
                                <a href="/">
                                    <span>
                                       HOME 
                                    </span>
                                    
                                </a>
                            </li>
                            <li>
                                <a href="/items">
                                    <span>
                                        PRODUCTS
                                    </span>
                                        
                                </a>
                            </li>
                            <li>
                                <a href="/pages/about">
                                    <span>
                                       ABOUT
                                    </span> 	
                                    
                                </a>
                                <?php /*
                                <div class="TonyM__mm TonyM__mm--simple TonyM__mm--anim_emersion" data-tm-w="280" data-tm-a-h="item-left" style="">
                                    <ul class="TonyM__list">
                                        <li><a href="/our-story">Our Story</a></li>
                                        <li><a href="/our-principles">Our Principles</a></li>
                                    </ul>
                                </div>
                                */ ?>
                            </li>
                            <li>
                                <a href="/blog">
                                    <span>
                                       BLOG
                                    </span>
                                </a>
                            </li>
                           <?php /*  <li>
                                <a href="/how-it-works">
                                    <span>
                                        HOW IT WORKS
                                    </span>
                                </a>
                            </li> 
                            <li>
                                <a href="/">
                                    <span>
                                        SUPPORT
                                    </span>
                                </a>
                                <div class="TonyM__mm TonyM__mm--simple TonyM__mm--anim_emersion" data-tm-w="280" data-tm-a-h="item-left" style="">
                                    <ul class="TonyM__list">
                                        <li><a href="/contact">Contact Us</a></li>
                                        <li><a href="/contact">Feedback</a></li>
                                    </ul>
                                </div>
                            </li> */ ?>
                            <li>
                                <a href="/contact">
                                    <span>
                                        CONTACT
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <div class="tt-header__sidebar">
                    <div class="tt-header__options">
                        <a href="#" class="tt-header__btn tt-header__btn-menu"><i class="icon-menu colorize-theme13-c"></i></a>
                        <div role="search" class="tt-header__search">
                            <form action="#" class="tt-header__search-form">
                                <input type="search" name="q" class="form-control" placeholder="Type product name...">
                            </form>
                            <div class="tt-header__search-dropdown"></div>
                            <a href="#" class="tt-header__btn tt-header__btn-open-search"><i class="icon-search colorize-theme13-c" title="Search for a product"></i></a>
                            <a href="#" class="tt-header__btn tt-header__btn-close-search"><i class="icon-cancel-1 colorize-theme13-c"></i></a>
                        </div>
                        <div>
                            <a href="#" class="tt-header__btn tt-header__btn-user"><i class="icon-user-outline colorize-theme13-c" title="My account"></i></a>
                            <div class="tt-header__user drop-down-menu">
                                <ul class="tt-list-toggle">
                                    @if(Auth::check())
                                    <li><a href="/my-account">My account</a></li>
                                    <li><a href="/membership/plans">Membership</a></li>
                                    @role('admin')
                                    <li><a href="/admin/dashboard">Site admin</a></li>
                                    @endrole {{--
                                    <li><a href="/cart/checkout">Checkout</a></li> --}}
                                    @if (\Session::has('return_user'))
                                        <li><a href="/ru">End Impersonation</a></li>
                                    @endif
                                    <li>
                                        <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>
                                    </li>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                    @else
                                    <li><a href="/login">Login</a></li>
                                    <li><a href="/register">Register</a></li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                        <a href="/wishlist" class="tt-header__btn tt-header__btn-wishlist">
                            <i class="icon-heart-empty-2 colorize-theme13-c"  title="Add to wishlist"></i>
                            <span class=" colorize-theme13-c" id="wishlist_count">0</span>
                        </a>
                        <div id="menu-cart">
                            <a href="/cart" class="tt-header__btn tt-header__btn-cart">
                                <i class="icon-shop24 colorize-theme13-c " title="Shopping cart"></i>
                                <span class="colorize-theme13-c cart_count" id="cart_count">0</span>
                            </a>
                            <div class="tt-header__cart">
                                <div id="header_cart" class="tt-header__cart-content drop-down-menu">

                                </div>
                            </div>
                        </div>
                        <div id="menu-cart-checkout">
                            <a href="/cart" class="tt-header__btn" id="menu-cart-checkout-a">
                                <i class="icon-shop24 colorize-theme13-c" title="Shopping cart"></i>
                                <span class="colorize-theme13-c cart_count" id="cart_count">0</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
