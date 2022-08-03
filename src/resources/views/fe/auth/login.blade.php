@extends('themes.'.(isset($theme)?$theme:'default').'.layouts.main')

@section('local_styles')
<style>
    .custom-login{
        margin-top: 50px; 
        box-shadow: 0 10px 16px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19) !important;
        max-width: 500px;
    }
    #theme input[type=email], #theme input[type=password], #theme input[type=search], #theme input[type=tel], #theme input[type=text], #theme select, #theme textarea {
        background-color: #fff;
        border-color: #ccc;
        color: #333;
    }
    button{
        /* border-radius: 5px!important; */
        box-shadow: 0 10px 16px 0 rgba(0,0,0,0.1),0 6px 5px 0 rgba(0,0,0,0.1) !important;
    }
    .errors{
        font-size: 11px!important;
        color:#FF7588!important;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="tt-login custom-login">
        <div class="text-center" style="padding: 20px">
            <h4 style="color:#777;">Log In With</h4>
        </div>
        <form method="POST" action="{{ route('login') }}">
         @csrf
            <div class="tt-form" style="padding-bottom:20px;">
                <div class="tt-form__form">
                    <label>
                        <div class="row">
                            <div class="col-md-8 offset-md-2 col-10 offset-1">
                                <div class="row">
                                    <div class="col-md-6 col-6">
                                        <button type="button" class="btn" style="width:100%;display:block;background-color:#3B5998;border:1px solid #3B5998;padding:15px 26px;font-size:13px;">
                                        <img src="/images/icons/facebook.png" style="width:18px;margin-right:5px;vertical-align:sub;"/>Facebook</button>
                                    </div>
                                    <div class="col-md-6 col-6">
                                        <button type="button" class="btn" style="width:100%;display:block;background-color:#fff;border:1px solid #ccc;padding:15px 26px;font-size:13px;color:#000;">
                                            <img src="/images/icons/google.png" style="width:18px;margin-right:5px;vertical-align:sub;"/> Google</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8 offset-md-2 col-10 offset-1">
                                <label style="color:#777;margin-bottom:1px;font-size:13px;">{{ __('Username') }}</label>
                                <input style="color:#777!important;" id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback errors">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-8 offset-md-2 col-10 offset-1">
                                <label style="color:#777;margin-bottom:1px;font-size:13px;">{{ __('Password') }} <a href="{{ route('password.request') }}" style="color:#abaaaa; text-decoration:underline;font-size:11px;padding-left:5px;">Forgot?</a></label>
                                <input style="color:#777!important;" id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required >
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback errors">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8 offset-md-2 col-10 offset-1">
                                <button type="submit" class="btn" style="width:100%;display:block;font-size:13px;"> {{ __('Login') }}</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8 offset-md-2 col-10 offset-1" style="text-align:center;">
                                <label style="color:#abaaaa; font-size:11px;margin-bottom:1px;"> Not a member? <a href="/register" style="color:#abaaaa; text-decoration:underline;">Register now</a></label>
                                <label style="color:#abaaaa; font-size:11px;"> <a href="/" style="color:#abaaaa; text-decoration:underline;">Take me Home</a></label>
                            </div>
                        </div>
                    </label>
                </div>
            </div>
            {{-- <div class="row ttg-mt--40">
                <div class="col-md-4 col-sm-6 offset-md-2">
                    <label class="tt-checkbox">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> 
                        <span></span>
                        <p>{{ __('Remember Me') }}</p>
                    </label>
                </div>
                <div class="col-md-4 col-sm-6 text-right"><a href="{{ route('password.request') }}"> {{ __('Forgot Your Password?') }}</a></div>
            </div> --}}
        </form>
    </div>
</div>
@endsection

