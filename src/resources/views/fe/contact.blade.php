@extends('themes.'.(isset($theme)?$theme:'default').'.layouts.main')
@section('local_styles')
@endsection
 
@section('content')
<div class="tt-layout tt-sticky-block__parent tt-layout__fullwidth">
    <div class="tt-layout__content">
        <div class="container">

            <div class="tt-contacts">
                <div class="container">
                    <div class="tt-page__cont-small">
                        <div class="tt-contacts__form tt-form">
                            <div class="tt-contacts__form_title tt-form__title tt-form__title--lg text-center">
                                @if($type == 'user')
                                <span>User contact</span> @elseif($type == 'affiliate')
                                <span>Affiliate contact</span> @elseif($type == 'feedback')
                                <span>Feedback contact</span> @elseif($type == 'wholesale')
                                <span>Wholesaler contact</span> @endif
                            </div>
                            <p class="ttg__required text-center">Your email address will not be published. Required fields are marked</p>
                            <form method="post" action="/store/send-contact-email">
                                @csrf
                                <input name="type" type="hidden" value="{{$type}}">
                                <div class="tt-contacts__form_inputs tt-form__form">
                                    <label>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <span class="ttg__required">Name:</span>
                                            </div>
                                            <div class="col-md-10">
                                                    <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}   colorize-theme6-bg" name="name" value="{{ old('name') }}" placeholder="Enter your name" required autofocus>
                                                    @if ($errors->has('name'))
                                                        <span class="invalid-feedback">
                                                            <strong>{{ $errors->first('name') }}</strong>
                                                        </span>
                                                    @endif
                                            </div>
                                        </div>
                                    </label>
                                    <label>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <span class="ttg__required">E-mail:</span>
                                            </div>
                                            <div class="col-md-10">
                                                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }} colorize-theme6-bg" name="email" value="{{ old('email') }}" placeholder="Enter your email" required autofocus>
                                                    @if ($errors->has('email'))
                                                        <span class="invalid-feedback">
                                                            <strong>{{ $errors->first('email') }}</strong>
                                                        </span>
                                                    @endif
                                            </div>
                                        </div>
                                    </label>
                                    <label>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <span class="">Phone:</span>
                                            </div>
                                            <div class="col-md-10">
                                                    <input id="phone" type="text" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }} colorize-theme6-bg" name="phone" value="{{ old('phone') }}" placeholder="Enter your phone" autofocus>
                                                    @if ($errors->has('phone'))
                                                        <span class="invalid-feedback">
                                                            <strong>{{ $errors->first('phone') }}</strong>
                                                        </span>
                                                    @endif
                                            </div>
                                        </div>
                                    </label>
                                    <label>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <span class="ttg__required">Message:</span>
                                            </div>
                                            <div class="col-md-10">
                                                <textarea name="message" class="form-control{{ $errors->has('message') ? ' is-invalid' : '' }} colorize-theme6-bg" placeholder="how can we help you?"></textarea>
                                                @if ($errors->has('message'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('message') }}</strong>
                                                </span>
                                            @endif
                                            </div>
                                        </div>
                                    </label>
                                    <div class="row">
                                        <div class="offset-md-2 col-md-10 text-center">
                                            <button class="btn btn--xs-flw">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
                <?php /*
					<div class="tt-contacts__adress">
                        <div class="row ttg-grid-padding--none">
                            <div class="col-md-6">
                                <div class="tt-contacts__map">
                                    <iframe src="https://www.google.com/maps/embed?pb=!1m10!1m8!1m3!1d179203.69595995915!2d12.3000326!3d45.4283372!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sua!4v1496761644226"
                                            frameborder="0" style="border:0" allowfullscreen></iframe>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="tt-contacts__info">
                                    <div class="tt-contacts__info_text text-center">
                                        <p>Address: 7563 St. Vicent Place, Glasgow</p>
                                        <p>Phone: +777 2345 7885: +777 2345 7886</p>
                                        <p>Hours : 7 Days a week from 10-00 am to 6-00 pm</p>
                                        <p>E-mail: <a href="mailto:info@mydomain.com">info@mydomain.com</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
					*/ ?>
            </div>
        </div>
    </div>
</div>
@endsection
 
@section('local_script')
@endsection