<!DOCTYPE html>
<html lang="en-US" lang="{{ app()->getLocale() }}">

<head>
    <title>{{ config('app.name', 'Properos') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1">
    {{-- <meta name="keywords" content="HTML5 Template"> --}}
    <meta name="description" content="{{env('APP_NAME', 'Properos')}} Store">
    <meta name="author" content="{{env('APP_NAME', 'Properos')}} inc">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('meta')
    @yield('meta_specific')
    <title>{{env('APP_NAME', 'Properos')}}</title>

    <!-- STYLESHEET -->
    <!-- FONTS -->
    <!-- Muli -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Muli:300,400,600,700,800%7CMontserrat:300,400,500,600,700%7COpen+Sans">
    <link rel="stylesheet" type="text/css" href="/be/app-assets/css/vendors.css">
    <!-- icon -->
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/themes/{{(isset($theme)?$theme:'default')}}/fe/fonts/icons/fontawesome/font-awesome.css">
    <!-- MyFont -->
    <link rel="stylesheet" href="/themes/{{(isset($theme)?$theme:'default')}}/fe/fonts/icons/myfont/css/myfont.css">
    <link rel="stylesheet" href="/themes/{{(isset($theme)?$theme:'default')}}/fe/fonts/icons/myfont/css/myfont-embedded.css">
    <link rel="stylesheet" href="/themes/{{(isset($theme)?$theme:'default')}}/fe/fonts/icons/myfont/css/animation.css">
    <!--[if lt IE 9]>
    <link rel="stylesheet" href="/fe/fonts/icons/myfont/css/myfont-ie7.css">
    <![endif]-->

    <!-- Vendor -->
    <link rel="stylesheet" href="/themes/{{(isset($theme)?$theme:'default')}}/vendor/bootstrap/v3/css/bootstrap.min.css">
    <link rel="stylesheet" href="/themes/{{(isset($theme)?$theme:'default')}}/vendor/bootstrap/v4/css/bootstrap-grid.css">
    <link rel="stylesheet" href="/themes/{{(isset($theme)?$theme:'default')}}/vendor/perfectscrollbar/css/perfect-scrollbar.min.css">
    <link rel="stylesheet" href="/themes/{{(isset($theme)?$theme:'default')}}/vendor/tonymenu/css/tonymenu.css">
    <link rel="stylesheet" href="/themes/{{(isset($theme)?$theme:'default')}}/vendor/revolution/css/settings.css">
    <link rel="stylesheet" href="/themes/{{(isset($theme)?$theme:'default')}}/vendor/revolution/css/layers.css">
    <link rel="stylesheet" href="/themes/{{(isset($theme)?$theme:'default')}}/vendor/revolution/css/navigation.css">
    <link rel="stylesheet" href="/themes/{{(isset($theme)?$theme:'default')}}/vendor/slick/slick.min.css">
    <link rel="stylesheet" href="/themes/{{(isset($theme)?$theme:'default')}}/vendor/magnificpopup/dist/magnific-popup.css">
    <link rel="stylesheet" href="/themes/{{(isset($theme)?$theme:'default')}}/vendor/rangeslider/css/ion.rangeSlider.css">
    <link rel="stylesheet" href="/themes/{{(isset($theme)?$theme:'default')}}/vendor/rangeslider/css/ion.rangeSlider.skinFlat.css">
    <link rel="stylesheet" href="/themes/{{(isset($theme)?$theme:'default')}}/vendor/swiper/css/swiper.min.css">
    <link rel="stylesheet" type="text/css" href="/be/app-assets/vendors/css/extensions/toastr.css">
    <link rel="stylesheet" href="/themes/{{(isset($theme)?$theme:'default')}}/vendor/fotorama/fotorama.css"> {{--
    <link rel="stylesheet" href="/fe/css/spinner.css"> --}}

    <!-- Custom -->
    <link rel="stylesheet" href="/themes/{{(isset($theme)?$theme:'default')}}/fe/css/style.css">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <style>
        .tt-footer__content {
            padding-bottom: 0px !important;
        }

        .drop-down-menu {
            background-color: #fff !important;
            border-left: 2px solid #fff !important;
            border-right: 2px solid #fff !important;
            border-bottom: 2px solid #fff !important;
        }

        .logo_container {
            position: absolute;
            width: 100%;
            height: 100%;
        }

        .footer {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            color: white;
            text-align: left;
        }

        #theme .tt-footer {
            background-color: #13919b!important;
        }

        #theme .tt-newsletter.tt-newsletter--style-01 .btn {
            background-color: #b18810;
            border-color: #b18810
        }

        .drop-down-menu {
            background-color: #fff !important;
            border-left: 2px solid #fff !important;
            border-right: 2px solid #fff !important;
            border-bottom: 2px solid #fff !important;
        }


        .img-bg {
            background-image: url('/themes/default/fe/images/logo-self-t1.png');
            background-repeat: no-repeat;
            background-origin: content-box;
            background-position: left bottom;
        }

        @media (min-width: 1200px) {
            .drop-down-menu {
                background-color: #fff !important;
                border-left: 2px solid #13919b !important;
                border-right: 2px solid #13919b !important;
                border-bottom: 2px solid #13919b !important
            }
        }
        @media (max-width: 576px) {
            .img-bg {
                background-size: 50%;
                background-position: left bottom;
            }
        }

        #theme .tt-footer__copyright {
            color: #fff;
        }

        #theme .colorize-theme13-bg {
            background-color: #13919b!important;
        }
        #theme .btn:not([class*="colorize-btn"]):not([class*="colorize-btn1"]), #theme .colorize-btn, #theme .colorize-btn1 {
            background-color: #13919b;
            border-color: #13919b;
        }
        @media only screen and (min-width: 1025px){
            #theme .btn:not([class*="colorize-btn"]):not([class*="colorize-btn1"]):hover, #theme .colorize-btn:hover, #theme .colorize-btn1:hover {
                background-color: #ffffff;
                border-color: #13919b;
                color: #13919b;
            }
        }
        #theme input[type=email]:focus, #theme input[type=password]:focus, #theme input[type=search]:focus, #theme input[type=tel]:focus, #theme input[type=text]:focus, #theme select:focus, #theme textarea:focus {
            background-color: #fff;
            border-color: #13919b;
        }

        #theme .tt-header--style-01 .tt-header__logo, #theme .tt-header--style-01 .tt-logo__curtain::before, #theme .tt-header--style-06 .tt-header__logo, #theme .tt-header--style-06 .tt-logo__curtain::before {
            background-color: #13919b!important;
            border-bottom: 1px solid #eee;
        }
        @media only screen and (min-width: 1025px){
            #theme .tt-header--style-01 .TonyM__panel>li>a, #theme .tt-header--style-02 .TonyM__panel>li>a, #theme .tt-header--style-04 .TonyM__panel>li>a, #theme .tt-header--style-06:not(.tt-header--transparent) .TonyM__panel>li>a {
                color: #13919b;
            }
        }

        #theme .colorize-theme13-c {
            color: #13919b!important;
        }

        #theme .tt-list-toggle a, #theme .tt-list-toggle a:active, #theme .tt-list-toggle a:link, #theme .tt-list-toggle a:visited {
            color: #13919b;
        }

        #theme .tt-newsletter.tt-newsletter--style-01 .btn {
            background-color: #2DCEE3;
            border-color: #2DCEE3;
        }
        @media only screen and (min-width: 1025px){
            #theme .tt-newsletter.tt-newsletter--style-01:not(.tt-newsletter__error) .btn:hover {
                background-color: #ffffff;
                border-color: #13919b;
            }
        }
        #theme .tt-footer__list-menu a, #theme .tt-footer__list-menu a:visited, #theme .tt-footer__list-menu a:active, #theme .tt-footer__list-menu a:link {
            color: #d7faf6 !important;
        }
        #theme .tt-footer__newsletter p {
            color: #d7faf6 !important;
        }

        #menu-cart{
            display: block;
        }
        #menu-cart-checkout{
            display: none;
        }

        .menu-header{
            top: 50px!important;
        }
        .message-top{
            padding-top:10px;
        }
        .show-button-mobile{
            display: none;
        }
        .show-button-desktop{
            display: initial;
        }

        @media only screen and (max-width: 768px){
            .message-top{
                padding-top:0px;
            }
            .show-button-mobile{
                display: initial;
            }
            .show-button-desktop{
                display: none;
            }
        }

    </style>
    @yield('local_styles')
</head>