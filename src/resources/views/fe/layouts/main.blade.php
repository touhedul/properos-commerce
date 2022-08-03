@include('themes.'.(isset($theme)?$theme:'default').'.layouts.header')
<body id="theme">
    <div class="tt-form">
        @if (session('unauthorized_state'))
        <div class="alert alert-success">
            {{ session('unauthorized_state') }}
        </div>
        @endif
        <div id="commerce-module">
            @if(Request::path() != 'login' && Request::path() != 'register')
            @include('themes.'.(isset($theme)?$theme:'default').'.layouts.menu') @endif
            @yield('content')
            @include('themes.'.(isset($theme)?$theme:'default').'.layouts.footer')
        </div>

        <script src="/be/app-assets/vendors/js/vendors.min.js" type="text/javascript"></script>
        <script src="/be/app-assets/vendors/js/extensions/toastr.min.js" type="text/javascript"></script>
        <script src="/themes/{{(isset($theme)?$theme:'default')}}/vendor/scrollsmooth/SmoothScroll.js"></script>
        <script src="/themes/{{(isset($theme)?$theme:'default')}}/vendor/jquery/jquery.min.js"></script>
        <script src="/themes/{{(isset($theme)?$theme:'default')}}/vendor/cookie/jquery.cookie.js"></script>
        <script src="/themes/{{(isset($theme)?$theme:'default')}}/vendor/jquery/ui/jquery-ui.min.js"></script>
        <script src="/themes/{{(isset($theme)?$theme:'default')}}/vendor/velocity/velocity.min.js"></script>
        <script src="/themes/{{(isset($theme)?$theme:'default')}}/vendor/modernizr/modernizr.js"></script>
        <script src="/themes/{{(isset($theme)?$theme:'default')}}/vendor/lazyload/jquery.lazy.min.js"></script>
        <script src="/themes/{{(isset($theme)?$theme:'default')}}/vendor/lazyload/jquery.lazy.plugins.min.js"></script>
        <script src="/themes/{{(isset($theme)?$theme:'default')}}/vendor/tonymenu/js/tonymenu.js"></script>
        <script src="/themes/{{(isset($theme)?$theme:'default')}}/vendor/perfectscrollbar/js/perfect-scrollbar.jquery.min.js"></script>
        <script src="/themes/{{(isset($theme)?$theme:'default')}}/vendor/revolution/js/jquery.themepunch.tools.min.js"></script>
        <script src="/themes/{{(isset($theme)?$theme:'default')}}/vendor/revolution/js/jquery.themepunch.revolution.min.js"></script>
        <script src="/themes/{{(isset($theme)?$theme:'default')}}/vendor/countdown/jquery.countdown.min.js"></script>
        <script src="/themes/{{(isset($theme)?$theme:'default')}}/vendor/moment/moment.js"></script>
        <script src="/themes/{{(isset($theme)?$theme:'default')}}/vendor/moment/moment-timezone.js"></script>
        <script src="/themes/{{(isset($theme)?$theme:'default')}}/vendor/slick/slick.min.js"></script>
        <script src="/themes/{{(isset($theme)?$theme:'default')}}/vendor/magnificpopup/dist/jquery.magnific-popup.min.js"></script>
        <script src="/themes/{{(isset($theme)?$theme:'default')}}/vendor/elevatezoom/jquery.elevateZoom-3.0.8.min.js"></script>
        <script src="/themes/{{(isset($theme)?$theme:'default')}}/vendor/stickyblock/sticky-sidebar.min.js"></script>
        <script src="/themes/{{(isset($theme)?$theme:'default')}}/vendor/rangeslider/js/ion.rangeSlider.min.js"></script>
        <script src="/themes/{{(isset($theme)?$theme:'default')}}/vendor/instafeed/instafeed.min.js"></script>
        <script src="/themes/{{(isset($theme)?$theme:'default')}}/vendor/jquery/jquery-bridget.js"></script>
        <script src="/themes/{{(isset($theme)?$theme:'default')}}/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
        <script src="/themes/{{(isset($theme)?$theme:'default')}}/vendor/masonry/masonry.pkgd.min.js"></script>
        <script src="/themes/{{(isset($theme)?$theme:'default')}}/vendor/swiper/js/swiper.min.js"></script>
        <script src="/themes/{{(isset($theme)?$theme:'default')}}/vendor/fotorama/fotorama.js"></script>
        <script src="/themes/{{(isset($theme)?$theme:'default')}}/vendor/jquery-cc-vidator/jquery.creditCardValidator.js"></script>
        <script src="/themes/{{(isset($theme)?$theme:'default')}}/js/loadingoverlay.min.js"></script>
        <script src="/themes/{{(isset($theme)?$theme:'default')}}/js/loadingoverlay_progress.min.js"></script>
        <script src="{!! asset('be/js/core.js') !!}" type="text/javascript"></script>
        <script>
            $(document).ready(function(){
                $.ajax({
                    url:'/announcement',
                    type: 'get',
                    success(response){
                        if(response.status > 0 && response.data != null && response.data.message){
                            var string = '<div style="min-height:50px;background-color:'+response.data.background_color+'; color:'+response.data.text_color+'!important; text-align:center;">\
                                            <div class="show-button-desktop" style="float:right;padding-right:2px;font-weight:bold;cursor:pointer;" onclick="removeCookie(\''+response.data.serial+'\')">\
                                                <a href="#" class="btn" style="color:'+response.data.text_color+';font-size:12px;margin-top:15px;border-radius: 5px;padding: 0px 5px;background:transparent; border: 1px solid '+response.data.text_color+'">close</a>\
                                            </div>\
                                            <div class="message-top" style="min-height:50px;"><p>'+response.data.message+'</p>\
                                                <br class="show-button-mobile"><a href="#" onclick="removeCookie(\''+response.data.serial+'\')" class="btn show-button-mobile" style="margin-bottom:5px;font-weight:bold;cursor:pointer;color:'+response.data.text_color+';font-size:12px;margin-top:15px;border-radius: 5px;padding: 0px 5px;background:transparent; border: 1px solid '+response.data.text_color+'">close</a>\
                                            </div>\
                                            </div>';
                            $('#show-announcement').html(string);
                            $('#show-announcement p').css('color', response.data.text_color);
                            $('#show-announcement p').css('margin-bottom', '0px');
                            $('#menu-header').addClass('menu-header');
                        }else{
                            $('#show-announcement').html('');
                            $('#menu-header').removeClass('menu-header');
                        }
                    }
                });
            })
            function removeCookie(serial){
                $.ajax({
                    url:'/cookie/remove/'+serial,
                    type: 'get',
                    success(response){
                        $('#show-announcement').html('');
                        $('#menu-header').removeClass('menu-header');
                    }
                });
            }
        </script>
        @yield('local_script')
        <script src="{{ asset('/themes/'.(isset($theme)?$theme:'default').'/modules/js/commerce.js') }}"></script>
        <script src="/themes/{{(isset($theme)?$theme:'default')}}/fe/js/app.js"></script>
        <script src="/themes/{{(isset($theme)?$theme:'default')}}/js/fe-scripts.js"></script>
        <script src="/themes/{{(isset($theme)?$theme:'default')}}/fe/api-calls/fe-api-calls.js"></script>
		<?php /*
        <!-- Facebook Pixel Code -->
        <script>
            !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window,document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
     fbq('init', '1773693189340840'); 
    fbq('track', 'PageView');
        </script>
        <noscript>
     <img height="1" width="1" 
    src="https://www.facebook.com/tr?id=1773693189340840&ev=PageView
    &noscript=1"/>
    </noscript>
        <!-- End Facebook Pixel Code -->
		*/ ?>
</body>

</html>