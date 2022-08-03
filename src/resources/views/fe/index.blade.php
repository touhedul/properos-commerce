@extends('themes.'.(isset($theme)?$theme:'default').'.layouts.main') 
@section('meta_specific')
<meta name="keywords" content="{{$keywords}}">
@endsection
@section('local_css')
<style>
    .bg-blue {
        background-color: #2196F3 !important;
    }

    .contact-ribbon {
        width: 100%;
        margin-left: 0px;
        margin-right: 0px;
        color: #fff !important;

        /* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#000000+0,000000+100&0.7+0,1+100 */
        background: -moz-linear-gradient(top, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 1) 100%);
        /* FF3.6-15 */
        background: -webkit-linear-gradient(top, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 1) 100%);
        /* Chrome10-25,Safari5.1-6 */
        background: linear-gradient(to bottom, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 1) 100%);
        /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#b3000000', endColorstr='#000000', GradientType=0);
        /* IE6-9 */
    }

    #theme .tt-shp-info,
    .tt-shp-info__strong {
        color: #fff !important;

    }

    #theme .tt-shp-info p {
        color: #fff !important;
    }

    #theme .tt-shp-info__design-02 [class^="icon-"] {
        color: #fff !important;
    }

    .tt-shp-info__section {
        padding: 0px !important;
    }

    @media only screen and (min-width: 1024px) {
        #theme .ttg-grid-border>[class^="col-"]:not(:first-child) {
            border-color: transparent;
        }
    }
	    @media only screen and (min-width: 768px) {
        .on-mobile {
			display: none !important;
		}
        .on-mobile {
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
    .section-ribbon {
		margin-bottom:20px;
		background: rgb(0,0,0) !important;
		color: #fbd127 !important;
    }
    .contact-ribbon *, .contact-ribbon i::before, .return-policy, #theme .tt-shp-info p {
		color: #fbd127 !important;
    }
	.tt-product__image {
		padding: 15px;
	}
	.tt-footer.tt-footer__01 {
		background: linear-gradient(to bottom, #a48c43,#faef96, #beaa63);
	}
</style>
@endsection
 
@section('content')
    
<div class="tt-layout tt-sticky-block__parent tt-layout__fullwidth">
        <div class="tt-layout__content">
    
            <div class="container">
                @if (session('status'))
                <div class="alert bg-primary alert-dismissible mb-2" role="alert" style="margin-bottom: 0px !important; text-align: center; font-size: 16px; color: #0f595e; border-radius: 0px">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <strong>{{ session('status') }}</strong>
                </div>
                @endif
                <div class="tt-sr" data-layout="fullscreen">
    
                    <div class="tt-sr__content" data-version="5.3.0.2">
                        <ul>
                            <li data-index="rs-3045" data-transition="fade" data-fstransition="none" data-fsmasterspeed="0" data-fsslotamount="7" data-masterspeed="150">
                                <img src="/fe/images/banner/banner_home_back.jpg" alt="Image name" class="rev-slidebg" data-bgposition="center center" data-bgfit="cover"
                                    data-bgrepeat="no-repeat" data-bgparallax="8">
    
                    <div class="tp-caption rs-parallaxlevel-3  on-mobile "
                            data-frames='[{"delay":0,"speed":2000,"frame":"0","from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;","to":"o:1;","ease":"Power4.easeInOut"},{"delay":"wait","speed":1000,"frame":"999","to":"y:[-100%];opacity:0;","ease":"Power3.easeInOut"}]'
                            data-x="center"
                            data-y="center"
                            data-whitespace="nowrap"
                            data-hoffset="76">  
                        <img src="/fe/images/banner/banner_home_recipients.png" style="min-width:300px !important;min-height:300px !important;">
                        <p style="text-align:center;font-size:5em;color:#fff;line-height:20px;font-weight:600;"><b style="font-weight:900;">ENVIRONMENTALLY</b><br>MINDFUL TECHNOLOGY<p>
                    </div>
    
                                    
                    <div class="tp-caption rs-parallaxlevel-3 tt-sr__text on-desktop text-center"
                            data-frames='[{"delay":0,"speed":2000,"frame":"0","from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;","to":"o:1;","ease":"Power4.easeInOut"},{"delay":"wait","speed":1000,"frame":"999","to":"y:[-100%];opacity:0;","ease":"Power3.easeInOut"}]'
                            data-x="200"
                            data-y="center"
                            data-whitespace="nowrap"
                            data-width="['auto']"
                            data-height="['auto']"
                            data-hoffset="76"
                            >  
                        <img src="/fe/images/banner/banner_home_recipients.png">
                        <p style="text-align:center;font-size:2em;color:#13919b;line-height: 35px;"><b style="font-weight:900;">ENVIRONMENTALLY</b><br>MINDFUL TECHNOLOGY<p>
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
                        <p style="text-align:center;font-size:2em;color:#fff;line-height: 1.5em;">YOU'LL NEVER THINK<br>ABOUT DOING LAUNDRY<br>THE SAME WAY!<p>
                        
                    </div>
    
    
                        </ul>
                    </div>
                </div>
                <div class="container-fluid ttg-cont-padding--none">
    
    
            <div class="row text-center" style="margin-top:35px;margin-bottom:35px;">
                <div class="col-md-10 offset-md-1" style="padding-left:40px;padding-right:40px;">
                    <p style="text-align: justify; font-size: 18px;">
    A Limited number of laundry detergents on the market today are able to clean clothes well,
    however in doing so they have to use many harsh chemicals such as <b>1,4-dioxane.,
    NPE (nonylphenol ethoxylate)</b> and <b>Phosphates</b> just to mention a few. Unfortunately,
    these and other chemicals damage our environment on a daily basis. But our environment
    is not the only thing being impacted, studies over the years are showing that using these
    chemicals on our clothes over long sustained periods of time could actually be harming our
    bodies too. It is thought that illnesses such as cancer for example could be linked to the
    usage of certain chemicals used in our homes when washing our clothes.
                    </p>
                    <p style="text-align: justify; font-size: 18px;">
    Some companies are trying to reduce or take away these harsh chemicals to help the
    environment but by doing so are reducing the effectiveness of the detergents and therefore
    not cleaning the clothes as well.
                    </p>
                    <p style="text-align: justify; font-size: 18px;">
    At Hiketron we want to protect mother nature, stay healthy and still have clean clothes. To
    achieve this noble goal, we have invented a revolutionary laundry detergent product line
    that does just that. We don’t use harsh chemicals at all, which helps protect our
    environment and our bodies. The detergents are highly concentrated too, so this not only
    supports our endeavor to save our planet, but it also increases your bank account balance.
    We do not want to sell you water at the price of gold! And to add an extra bonus, our
    detergents not only clean clothes amazingly well, but they also have a great scent which
    lasts so much longer than average detergents in the market.
                    </p>
                        </div> 
                    </div>
    
    
                    <products-index :categories="{{isset($categories) ? json_encode($categories) : '[]' }}" :recommended_product="{{isset($recommended_product) ? json_encode($recommended_product) : '[]'}}"
                        :collections="{{isset($collections) ? json_encode($collections) : '[]'}}">
                </div>
    
    
                {{--
                <div class="tt-home__shipping-info-01">
                    <div class="tt-shp-info tt-shp-info__design-01">
                        <div class="row ttg-grid-padding--none ttg-grid-border">
                            <div class="col-lg-4">
                                <a href="#" class="tt-shp-info__section ">
                        <i class="icon-phone"></i>
                        <div class="tt-shp-info__strong">+1 281-954-1557</div>
                    </a>
                            </div>
                            <div class="col-lg-4">
                                <a href="#" class="tt-shp-info__section ">
                        <i class="icon-box"></i>
                        <div class="tt-shp-info__strong">Free Shipping</div>
                        <p>Shipping prices for any form of delivery and order’s cost is constant - $49. A
                            free shipping is available for orders <span>more than $99.</span></p>
                    </a>
                            </div>
                            <div class="col-lg-4">
                                <a href="#" class="tt-shp-info__section ">
                        <i class="icon-left"></i>
                        <div class="tt-shp-info__strong">Returns and Exchanges</div>
                        <p>Any goods, that was bought in our online store, can be returned during <span>30 days</span>
                            since purchase date.</p>
                    </a>
                            </div>
                        </div>
                    </div>
                </div> --}} {{--
                <div class="row" style="margin-left: 0px; margin-right: 0px;">
                    <div class="col-md-12">
                        <div style="text-align: center">
                            <span>Powered by:</span>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <img src="/images/creditCard/authorize.png" style="width: 150px; height: auto">
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
    @endsection
 
@section('local_script')
@endsection
