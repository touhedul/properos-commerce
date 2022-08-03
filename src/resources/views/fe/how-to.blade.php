@extends('themes.'.(isset($theme)?$theme:'default').'.layouts.main')
@section('local_styles')
<style>
    .tt-about__info div,
    .tt-about__info p {
        max-width: 820px;
    }

    #theme .tt-about__info {
        background-color: #fff;
    }


    .header-image {
        background-image: url('/images/bubbles-1070788_1920.jpg');
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-position: center;
        width: 120%;
        height: 500px;
        width: 950%;
        opacity: 0.85;
    }
</style>
@endsection
 
@section('content')
<div class="tt-layout tt-sticky-block__parent tt-layout__fullwidth">
    <div class="tt-layout__content">
        <div class="container">
            {{--
            <div class="tt-page__breadcrumbs">
                <ul class="tt-breadcrumbs">
                    <li><a href="/"><i class="icon-home"></i></a></li>
                    <li><a href="/how-it-works">How it works</a></li>
                </ul>
            </div> --}}
            <div class="tt-about" style="margin-bottom: 0px; margin-top: 0px;">
                <div class="row">
                    <div class="header-image">

                    </div>
                </div>
                <div class="row ttg-grid-padding--none">
                    <div class="col-md-12">
                        <div class="tt-about__info">
                            <div>How it works</div>
                            <p style="text-align: center; font-size: 18px">Lorem ipsum dolor sit amet conse ctetur adipisicing elit, sed do eiusmod tempor incididunt ut
                                labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="tt-about__info" style="text-align: left !important">
                            <div style="text-align: left; font-size: 16px">Duis aute irure dolor in reprehenderit in?</div>
                            <p style="text-align: left; font-size: 18px">Lorem ipsum dolor sit amet conse ctetur adipisicing elit, sed do eiusmod tempor incididunt ut
                                labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
                                laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in
                                voluptate velit esse cillum dolore.</p>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="tt-about__info" style="text-align: left !important">
                            <div style="text-align: left; font-size: 16px">What we believe</div>
                            <p style="text-align: left; font-size: 18px">Lorem ipsum dolor sit amet conse ctetur adipisicing elit, sed do eiusmod tempor incididunt ut
                                labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
                                laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in
                                voluptate velit esse cillum dolore.</p>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="tt-about__info" style="text-align: left !important">
                            <div style="text-align: left; font-size: 16px"> Duis aute irure dolor</div>
                            <p style="text-align: left; font-size: 18px">Lorem ipsum dolor sit amet conse ctetur adipisicing elit, sed do eiusmod tempor incididunt ut
                                labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
                                laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in
                                voluptate velit esse cillum dolore.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{--
            <div class="tt-page__name-sm text-center">
                <h2>Interested in Working Together?</h2>
                <p>Lorem ipsum dolor sit amet conse ctetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore
                    magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
                    commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore.</p>
                <a href="#" class="btn">Get in touch with us</a>
            </div> --}}
        </div>
    </div>
</div>
@endsection
 
@section('local_script')
@endsection