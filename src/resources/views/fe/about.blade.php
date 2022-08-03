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
        background-image: url('/images/banner/company.jpg');
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-position: center;
        width: 120%;
        height: 500px;
        width: 950%;
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
                            <img src="/fe/images/banner/banner_company_back.jpg" alt="Image name" class="rev-slidebg" data-bgposition="center center" data-bgfit="cover"
                                data-bgrepeat="no-repeat" data-bgparallax="8">

                <div class="tp-caption rs-parallaxlevel-3  on-mobile "
                        data-frames='[{"delay":0,"speed":2000,"frame":"0","from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;","to":"o:1;","ease":"Power4.easeInOut"},{"delay":"wait","speed":1000,"frame":"999","to":"y:[-100%];opacity:0;","ease":"Power3.easeInOut"}]'
                        data-x="center"
                        data-y="center"
                        data-whitespace="nowrap"
                        data-hoffset="76">  
                    <img src="/fe/images/banner/banner_logo.png" style="min-width:300px !important;min-height:300px !important;">
					<p style="text-align:center;font-size:5em;color:#fff;line-height:20px;font-weight:600;margin-top:20px;"><b style="font-weight:900;font-size:1.5em;">OUR COMPANY</b><br>OUR VALUES<p>
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
					<p style="text-align:center;font-size:2em;color:#fff;line-height: 1.5em;"><b style="font-weight:900;font-size:1.5em;">OUR COMPANY</b><br>OUR VALUES<p>
					
                </div>
				
                        </li>

                    </ul>
                </div>
            </div>		
            <div class="tt-about" style="margin-bottom: 0px; margin-top: 0px;">
                <div class="row ttg-grid-padding--none">
                    <div class="col-md-12">
                        <div class="tt-about__info">
                            <div>Our Story</div>
                            <p style="text-align: justify; font-size: 18px">
HIKETRON isn’t the first company setting out to revolutionize the cleaning products
industry, but we’re the first company that will deliver on that lofty promise. We see doing it
in a sustainable and socially responsible manner as not only good business, but good
stewardship and a mandate.
			    </p>
                            <p style="text-align: justify; font-size: 18px">
Founded in Brookshire, Texas by a team that believes in creating green and sustainable
products without compromising performance. After trying multiple products in the market
for years, we were always faced with a dilemma when shopping at the grocery store. Green
and sustainable products, deliver a great environmental profile but lack in performance,
whereas, traditional products made with harsh chemicals, deliver in performance and lack
the sustainability and safety needed to sustain a healthy lifestyle.
                            </p>
                            <p style="text-align: justify; font-size: 18px">
Multiple companies claim that their products are green even though most of them aren’t.
The concept of green and sustainable products is a little misunderstood and most
companies are taking advantages of this fact.
                            </p>
                            <p style="text-align: justify; font-size: 18px">
Tara and her husband started Hiketron to educate consumers about the true meaning of
green and sustainable chemicals by creating safe products that are actually non-harmful to
consumers. We plan to deliver in our promise by designing products with the least harmful
ingredients in the most economical way possible.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="tt-about__info">
                            <div>What we believe</div>
                    <div class="row">                            
                    <div class="col-md-6">                            
                            <p style="text-align: justify; font-size: 18px">
				<b>Our Vision for a Different Kind of Company</b>
			    </p>
                            <p style="text-align: justify; font-size: 18px">
We believe in progress and innovation without compromising human values and aspire to
bring positive changes to how we clean our homes and bodies while protecting the
environment. We challenge our scientists to develop home care, personal care products,
and luxurious, user-friendly and truly smart household dispensers, to enhance people’s
health, safety and their quality of life.
			    </p>
                            <p style="text-align: justify; font-size: 18px">
As a company we’re obsessed with connecting and engaging our employees, customers and
shareholders in everything we do, to create a win-win environment where everyone in our
community is served, respected and fully satisfied.
			    </p>
		    </div>    
                    <div class="col-md-6">                            
                            <p style="text-align: justify; font-size: 18px">
<b>Our Mission</b>
			    </p>
                            <p style="text-align: justify; font-size: 18px">
To be the premier manufacturer of smart home devices to dispense environmentally safe
home care and personal care products. To help people live a cleaner and more convenient
lifestyle by delivering new user experiences through improved product functionality,
added benefits and creative design.
			    </p>
                            <p style="text-align: justify; font-size: 18px">
<b>Our Core Values</b>
			    </p>
                            <p style="text-align: justify; font-size: 18px">
<ul class="text-left" style="text-align: justify; font-size: 18px;list-style-type:disc;list-style-position:inside;color:#777777;font-weight:400;">
<li>Humility is our foundation</li>
<li>Innovation is our engine</li>
<li>Kindness is what we live for</li>
<li>Excellence is what we strive for</li>
<li>Trust is what we earn everyday</li>
<li>Resilience is the key to our success</li>
<li>Outperformance is our commitment</li>
<li>Nimbleness is how we learn and adapt</li>
</ul>                            
                            </p>
		    </div>    
		    </div>    
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="tt-about__info">
                            <div>Our team</div>
                            <p style="text-align: justify; font-size: 18px">
Committed to enhancing lives through innovation and sustainability, our team brings an
enviable track record in chemistry, engineering, information technology and business
acumen to change the way people clean their homes. 
                            
                                <div class="row" style="width: 100%">
                                    <div class="col-md-3">
                                        <p class="ttg-image-translate--left ttg-animation-disable--sm" style="font-size:18px;font-weight:bold;">
                                    	    <img class="img-circle" alt="Image name" src="/images/team/aziz.jpg" srcset="/images/team/aziz.jpg" style="display: inline;width: 150px;padding-top: 15px;padding-bottom: 15px; height:  auto; ">
                                    	    <br>Aziz Hikem
                                    	    <br>Founder, CEO
                                        </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p class="ttg-image-translate--left ttg-animation-disable--sm" style="font-size:18px;text-align: justify;">
Passion, commitment
and faith are the three
success drivers and
core values our team
holds dear. A great
management team
came together under
the leadership of Aziz
Hikem, Founder, CEO,
and CTO. Hikem holds
degrees in chemistry,
chemical engineering
and an MBA with
more than two
decades of business
success and over a
dozen patents in the
chemical business.
Growing up in his
father’s jewelry
business made him
entrepreneurial at a
young age by default.
The skills making him
who he is today,
highest standards,
perseverance,
precision and a
tireless work ethic,
were learned working
with his father. They
guide him today as
they will in the years
to come.                                    
</p>
                                    </div>
                                <div class="row" style="width: 100%">
                                </div>
                                    <div class="col-md-3">
                                        <p class="ttg-image-translate--left ttg-animation-disable--sm" style="font-size:18px;font-weight:bold;">
                                    	    <img class="img-circle" alt="Image name" src="/images/team/tara.jpg" srcset="/images/team/tara.jpg" style="display: inline;width: 150px;padding-top: 15px;padding-bottom: 15px; height:  auto;;">
                                    	    <br>Tara Lee Hikem
                                    	    <br>Founder, President
                                        </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p class="ttg-image-translate--left ttg-animation-disable--sm" style="font-size:18px;text-align: justify;">
Tara Lee Hikem, a seasoned
chemical engineer and Texas A&amp;M
graduate, has a proven record of
leadership and process
improvement. Leading day-to-day
operations as president we are
totally confident in her guiding
Hiketron’s operations with success
and purpose. In addition to
technical skills and business
acumen, Tara’s most valuable skill
is a people-first personality making
her easy to trust, and a natural
leader helping employees to
achieve their full potential. Tara
holds one Patent along with her
Husband Aziz that will change how
people do laundry forever.                                    
</p>                                    
                                    </div>
                                    <?php /*
                                    <div class="col-md-4">
                                        <a href="$" class="ttg-image-translate--left ttg-animation-disable--sm"><img alt="Image name" src="/images/avatar-placeholder.png" srcset="/images/avatar-placeholder.png" style="display: inline;width: 150px;padding-top: 15px;height:  auto;;"></a>
                                    </div>
                                    */ ?>
                                </div>
                            </p>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
 
@section('local_script')
@endsection
