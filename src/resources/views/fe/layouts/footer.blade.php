<div class="row contact-ribbon">
    <div class="col-md-8 offset-md-2">
        <div class="tt-shp-info tt-shp-info__design-02 tt-shp-info__align--left" style="background: transparent">
            <div class="row ttg-grid-padding--none ttg-grid-border ttg-grid-border-c--white">
                <div class="col-lg-4">
                    <a href="tel:+17863940532" class="tt-shp-info__section tt-shp-info__align--left ">
                                        <i class="icon-phone"></i>
                                        <div>
                                            <div class="tt-shp-info__strong"><div class="tt-shp-info__strong">+1 786 394 0532</div></div>
                                            <?php /* <p><br></p> */ ?>
                                        </div>      
                                    </a>
                </div>
                <div class="col-lg-4">
                    <span class="tt-shp-info__section tt-shp-info__align--left">
                                <i class="icon-box"></i>
                                <div>
                                    <div class="tt-shp-info__strong">Free Shipping</div>
                                    <?php /* <p>On orders over $99</p> */ ?>
                                </div>
                            </span>
                </div>
                <div class="col-lg-4">
                    <a href="#" class="tt-shp-info__section tt-shp-info__align--left ">
                                <i class="icon-reply"></i>
                                <div>
                                    <div class="tt-shp-info__strong">Return and Exchanges</div>
                                    <p class="return-policy">Up to 14 days since purchase date</p>
                                </div>
                            </a>
                </div>

            </div>
        </div>
        <div class="row text-center">
            <div class="col-md-12" style="padding-bottom: 5px">
                <div>{{env("CONTACT_EMAIL")}} &#8226 Miami, FL 33172, USA </div>
            </div>
        </div>
    </div>
</div>

<!-- FOOTER -->
<footer>
    <div class="tt-footer tt-footer__01">
        <div class="tt-footer__content img-bg">
            <div class="container">
                <div class="row">
                    <div class="col-md-2">
                       {{--  <a href="/" class="tt-logo">
                            <img src="/fe/images/logo.png" alt="Image name">
                        </a> --}}
                    </div>
                    <div class="col-lg-6">
                        <div class="tt-footer__list-menu ">
                            <div class="row">
                                <div class="col-sm-4">
                                    <ul>
                                        {{--
                                        <li><a href="/about" class=" colorize-theme13-c">About us</a></li> --}} {{--
                                        <li><a href="/how-it-works" class=" colorize-theme13-c">How it works</a></li> --}}

                                        <li><a href="/pages/company" class=" colorize-theme13-c" style="font-size: 18px">COMPANY</a></li>
                                        <?php /* <li><a href="/our-principles" class=" colorize-theme13-c">Our Principles</a></li> */?>
                                        <li><a href="/items" class=" colorize-theme13-c">Products</a></li>
                                        {{-- <li><a href="/careers" class=" colorize-theme13-c">Careers</a></li> --}}
                                        <li><a href="/blog" class=" colorize-theme13-c">Blog</a></li>
                                        <li><a href="/my-account" class=" colorize-theme13-c">Account</a></li>
                                        <li><a href="/contact" class=" colorize-theme13-c">Contact Us</a></li>
                                        <li><a href="/contact?type=wholesale" class=" colorize-theme13-c">Wholesale</a></li>
                                        <li><a href="/contact?type=affiliate" class=" colorize-theme13-c">Affiliates</a></li>
                                    </ul>
                                </div>
                                <div class="col-sm-4">
                                    <ul>
                                        <li><a href="#" class=" colorize-theme13-c" style="font-size: 18px">MY ACCOUNT</a></li>
                                        @if(!Auth::check())
                                        <li><a href="/register" class=" colorize-theme13-c">Register</a></li>
                                        <li><a href="/login" class=" colorize-theme13-c">Sign In</a></li>
                                        @endif
                                        <li><a href="/cart" class=" colorize-theme13-c">My Cart</a></li>
                                        <li><a href="/wishlist" class=" colorize-theme13-c">My Wishlist</a></li>
                                        {{-- <li><a href="/my-account" class=" colorize-theme13-c">Track My Order</a></li> --}}
                                        <li><a href="/my-account#profile" class=" colorize-theme13-c">Change Password</a></li>
                                        <li><a href="/my-account#orders" class=" colorize-theme13-c">Order History</a></li>
                                        <?php /* <li><a href="/help" class=" colorize-theme13-c">Help</a></li> */ ?>
                                        <li><a href="/contact?type=feedback" class=" colorize-theme13-c">Feedback</a></li>
                                    </ul>
                                </div>
                                <div class="col-sm-4">
                                    <ul>
                                        <li><a href="#" class=" colorize-theme13-c" style="font-size: 18px">RESOURCES</a></li>
                                        <li><a href="/pages/faq" class=" colorize-theme13-c">FAQs</a></li>
                                        <li><a href="/pages/privacy" class=" colorize-theme13-c">Privacy Policy</a></li>
                                        <li><a href="/pages/terms" class=" colorize-theme13-c">Terms of Service</a></li>
                                        <li><a href="/pages/shipping-returns-policy" class=" colorize-theme13-c">Shippings & Returns Policy</a></li>
                                        {{-- <li><a href="/site-map" class=" colorize-theme13-c">Site Map</a></li> --}}
                                        <?php /* <li><a href="/engage-us" class=" colorize-theme13-c">Engage with Us</a></li> */ ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <span class="tt-footer__title" style="margin-bottom:0px;text-transform: uppercase;">Newsletter Signup</span>
                        <div class="tt-footer__newsletter">
                            <p style="line-height: 1.5;">Sign up for our e-mail and be the first to get our special offers!</p>
                            <form class="tt-newsletter tt-newsletter--style-01" style="margin-bottom:20px;">
                                <input type="email" name="email" class="form-control" placeholder="Enter your e-mail">
                                <button class="btn">
                                    <i class="tt-newsletter__text-wait"></i>
                                    <span class="tt-newsletter__text-default">Subscribe!</span>
                                    <span class="tt-newsletter__text-error"><i class="icon-exclamation"></i>Please provide a valid email address!</span>
                                    <span class="tt-newsletter__text-complete"><i class="icon-ok"></i>Check your inbox!</span>
                                </button>
                            </form>
                        </div>
                        <div class="text-center">
                            <span class="tt-footer__title" style="text-transform: uppercase; text-align: center;margin-bottom:2px;">Engage with us</span>
                            <hr style="margin: 0.5rem 5rem;">
                            <span class="tt-footer__title" style="text-align: center;font-size:16px;">
                                <i class="icon-mail" style="color:#fff;"></i>
                                <a style="color:#fff" href="mailto:{{env('MAIL_FROM_ADDRESS', 'support@properos.com')}}?subject={{env('APP_NAME', 'Properos')}}">{{env('MAIL_FROM_ADDRESS', 'support@properos.com')}}</a>
                            </span>
                        </div>
                        <div class="tt-footer__social">
                            {{-- <div class="tt-social-icons tt-social-icons--style-01"> --}}
                            <div class="text-center">
                                <a href="https://www.facebook.com/hiketron/" target="_blank">
                                    {{-- <i class="icon-facebook"></i> --}}
                                    <img src="/images/footer/icon_facebook.png" style="width:40px;">
                                </a>
                                <a href="https://plus.google.com/102982079586755494736" target="_blank">
                                    {{-- <i class="icon-gplus"></i> --}}
                                    <img src="/images/footer/icon_google.png" style="width:40px;">
                                </a>
                                <a href="https://twitter.com/HiketronInc" target="_blank">
                                    {{-- <i class="icon-twitter"></i> --}}
                                    <img src="/images/footer/icon_twitter.png" style="width:40px;">
                                </a>
                                <a href="https://www.instagram.com/hiketron/" target="_blank">
                                    {{-- <i class="icon-instagram-1"></i> --}}
                                    <img src="/images/footer/icon_instagram.png" style="width:40px;">
                                </a>
                                <a href="https://www.pinterest.com/hiketron" target="_blank">
                                    {{-- <i class="icon-instagram-1"></i> --}}
                                    <img src="/images/footer/icon_pinterest.png" style="width:40px;">
                                </a>
                                <a href="https://www.youtube.com/channel/UC-_9kHNhLNdr80dsRtXnjvA/featured?disable_polymer=1" target="_blank">
                                    {{-- <i class="icon-instagram-1"></i> --}}
                                    <img src="/images/footer/icon_youtube.png" style="width:40px;">
                                </a>
                                <?php /*
                                <a href="#" class="tt-btn">
                                    <i class="icon-youtube-play"></i>
                                </a>
                                <a href="#" class="tt-btn">
                                    <i class="icon-pinterest"></i>
                                </a>
                                <a href="#" class="tt-btn">
                                    <i class="icon-skype"></i>
                                </a>
                                <a href="#" class="tt-btn">
                                    <i class="icon-behance"></i>
                                </a>
								*/ ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 text-center">
                        <a href="#">
                            <img src="/images/footer/satisfaction_logo.png" style="width:80%;">
                        </a>
                        <a href="https://www.authorize.net" target="_blank">
                            <img src="/images/footer/authorize_logo.png" style="width:80%;">
                        </a>
                    </div>
                    <div class="col-lg-12 text-center">
                        <span class="tt-footer__copyright">&copy; 2018 {{env('APP_NAME', 'Properos')}} Inc. All Rights Reserved.</span>
                    </div>
                </div>
            </div>
        </div>
        <a href="#" class="tt-footer__to-top tt-footer__to-top-desktop">
            <i class="icon-up-open-1"></i>
            <i class="icon-up"></i><span>Top</span>
        </a>
    </div>
</footer>