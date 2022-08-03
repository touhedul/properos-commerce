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
        background-image: url('/images/rawpixel-585641-unsplash.jpg');
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-position: center;
        width: 120%;
        height: 500px;
        width: 950%;
        opacity: 0.9
    }
</style>
@endsection
 
@section('content')
<div class="tt-layout tt-sticky-block__parent tt-layout__fullwidth">
    <div class="tt-layout__content">
        <div class="container">
            {{-- <div class="tt-page__breadcrumbs">
                <ul class="tt-breadcrumbs">
                    <li><a href="/"><i class="icon-home"></i></a></li>
                    <li><a href="/about">About us</a></li>
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
                            <div>Our Story</div>
                            <p style="text-align: center; font-size: 18px">Comming soon...</p>
                        </div>
                    {{-- </div>
                    <div class="col-md-12">
                        <div class="tt-about__info">
                            <div>What we believe</div>
                            <p style="text-align: justify; font-size: 18px">Lorem ipsum dolor sit amet conse ctetur adipisicing elit, sed do eiusmod tempor incididunt ut
                                labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
                                laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in
                                voluptate velit esse cillum dolore.</p>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="tt-about__info">
                            <div>Our team</div>
                            <p style="text-align: justify; font-size: 18px">Lorem ipsum dolor sit amet conse ctetur adipisicing elit, sed do eiusmod tempor incididunt ut
                                labore et dolore magna aliqua.
                                <div class="row" style="width: 100%">
                                    <div class="col-md-4">
                                        <a href="$" class="ttg-image-translate--left ttg-animation-disable--sm"><img alt="Image name" src="/images/avatar-placeholder.png" srcset="/images/avatar-placeholder.png" style="display: inline;width: 150px;padding-top: 15px;padding-bottom: 15px; height:  auto;;"></a>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="$" class="ttg-image-translate--left ttg-animation-disable--sm"><img alt="Image name" src="/images/avatar-placeholder.png" srcset="/images/avatar-placeholder.png" style="display: inline;width: 150px;padding-top: 15px;padding-bottom: 15px; height:  auto;;"></a>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="$" class="ttg-image-translate--left ttg-animation-disable--sm"><img alt="Image name" src="/images/avatar-placeholder.png" srcset="/images/avatar-placeholder.png" style="display: inline;width: 150px;padding-top: 15px;height:  auto;;"></a>
                                    </div>
                                </div>
                            </p>
                        </div>

                    </div> --}}
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

            {{--<div class="tt-post hidden-xs">
                <div class="tt-post__slider tt-post__slider--bg slick-slider">
                    <div aria-live="polite" class="slick-list draggable">
                        <div class="slick-track" role="listbox" style="opacity: 1; width: 5709px;">
                            <div class="tt-post__content-quote slick-slide slick-current slick-active" data-slick-index="0" aria-hidden="false" tabindex="-1"
                                role="option" aria-describedby="slick-slide00" style="width: 1903px; position: relative; left: 0px; top: 0px; z-index: 1000; opacity: 1; transition: opacity 1000ms ease;">
                                <i class="icon-quote-1"></i>
                                <div class="tt-post__content-quote_title">Lorem ipsum dolor sit amet conse ctetur adipisicing elit!
                                </div>
                                <p class="colorize-text-c">Dolor sit amet conse ctetur adipisicing elit, sed do eiusmod tempor incididunt ut labore
                                    et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
                                    nisi ut aliquip.</p>
                                <div class="tt-post__content-quote_quote">– Aziz Hikem</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="tt-post__slider-nav tt-post__slider-nav--fixed-c tt-post__slider-nav--dark tt-post__slider-nav--arrows-none">
                    <div role="toolbar"><i class="icon-left-open-big slick-arrow" style="display: inline;"></i><i class="icon-right-open-big slick-arrow"
                            style="display: inline;"></i>
                        <ul class="slick-dots" role="tablist" style="display: flex;">
                            <li class="slick-active" aria-hidden="false" role="presentation" aria-selected="true" aria-controls="navigation00" id="slick-slide00"><button type="button" data-role="none" role="button" aria-required="false" tabindex="0">1</button></li>
                            <li aria-hidden="true" role="presentation" aria-selected="false" aria-controls="navigation01" id="slick-slide01" class=""><button type="button" data-role="none" role="button" aria-required="false" tabindex="0">2</button></li>
                            <li aria-hidden="true" role="presentation" aria-selected="false" aria-controls="navigation02" id="slick-slide02" class=""><button type="button" data-role="none" role="button" aria-required="false" tabindex="0">3</button></li>
                        </ul>
                    </div>
                </div> 
            </div>--}}
        </div>
    </div>
</div>
@endsection
 
@section('local_script')
@endsection