@extends('themes.'.(isset($theme)?$theme:'default').'.layouts.main')
@section('local_styles')
<style>
    .tt-about__info div,
    .tt-about__info p {
        // max-width: 820px;
    }

    #theme .tt-about__info {
        background-color: #fff;
    }

    .header-image {
        background-image: url('/images/banner/blog.jpg');
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-position: top;
        background-size: cover;
        height: 500px;
        width: 100% !important;
        opacity: 0.9
    }
    @media only screen and (min-width: 768px) {
        .on-mobile {
			display: none !important;
		}
        .on-desktop {
			desktop: block;
		}
    }
    @media only screen and (max-width: 767px) {
        .on-mobile {
			display: block;
		}
        .on-desktop {
			display: none !important;
		}
    }		
</style>
@endsection
@section('content')
<div class="tt-layout tt-sticky-block__parent tt-layout__fullwidth">
    <div class="tt-layout__content">
        <div class="container">
            <div class="tt-sr" data-layout="fullscreen">
                <div class="tt-sr__content" data-version="5.3.0.2">
                    <ul>
                        <li data-index="rs-3045" data-transition="fade" data-fstransition="none" data-fsmasterspeed="0" data-fsslotamount="7" data-masterspeed="150">
                            <img src="/fe/images/banner/banner_blog_back.jpg" alt="Image name" class="rev-slidebg" data-bgposition="center center" data-bgfit="cover"
                                data-bgrepeat="no-repeat" data-bgparallax="8">

                <div class="tp-caption rs-parallaxlevel-3  on-mobile "
                        data-frames='[{"delay":0,"speed":2000,"frame":"0","from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;","to":"o:1;","ease":"Power4.easeInOut"},{"delay":"wait","speed":1000,"frame":"999","to":"y:[-100%];opacity:0;","ease":"Power3.easeInOut"}]'
                        data-x="center"
                        data-y="center"
                        data-whitespace="nowrap"
                        data-hoffset="76">  
                    <img src="/fe/images/banner/banner_logo.png" style="min-width:300px !important;min-height:300px !important;">
					<p style="text-align:center;font-size:5em;color:#fff;line-height:20px;font-weight:600;margin-top:20px;"><b style="font-weight:900;font-size:1.5em;">INDUSTRY</b><br>NEWS &amp; OPINIONS<p>
                </div>
									
                <div class="tp-caption rs-parallaxlevel-3 on-desktop text-center"
						data-frames='[{"delay":0,"speed":2000,"frame":"0","from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;","to":"o:1;","ease":"Power4.easeInOut"},{"delay":"wait","speed":1000,"frame":"999","to":"y:[-100%];opacity:0;","ease":"Power3.easeInOut"}]' 
						data-x="1200"
                        data-y="center"
                        data-whitespace="nowrap"
						data-width="['auto']"
                        data-height="['auto']"
                        data-hoffset="76">  
                    <img src="/fe/images/banner/banner_logo.png">
					<p style="text-align:center;font-size:2em;color:#fff;line-height: 1.5em;"><b style="font-weight:900;font-size:1.5em;">INDUSTRY</b><br>NEWS &amp; OPINIONS<p>
					
                </div>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>
<div id="cms-module" class="content">
    <blog-index :posts="{{$posts}}"></blog-index>
</div>
@endsection
@section('local_script')
<script src="{{ asset('fe/js/modules/cms.js') }}"></script>
@endsection