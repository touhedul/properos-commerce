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
            <h4 style="color:#777;">Register</h4>
        </div>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="tt-form" style="padding-bottom:20px;">
                <div class="tt-form__form">
                    <label>
                    <div class="row">
                        <div class="col-md-8 offset-md-2 col-10 offset-1">
                            <label style="color:#777;margin-bottom:1px;font-size:13px;"  class="ttg__required">First Name</label>
                            <input style="color:#777!important;" id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}"  required autofocus>
                            @if ($errors->has('name'))
                                <span class="invalid-feedback errors">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-md-8 offset-md-2 col-10 offset-1">
                            <label style="color:#777;margin-bottom:1px;font-size:13px;"  class="ttg__required">Last Name</label>
                            <input style="color:#777!important;" id="lastname" type="text" class="form-control{{ $errors->has('lastname') ? ' is-invalid' : '' }}" name="lastname" value="{{ old('lastname') }}"  required>
                            @if ($errors->has('lastname'))
                                <span class="invalid-feedback errors">
                                    <strong>{{ $errors->lastname('lastname') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-md-8 offset-md-2 col-10 offset-1">
                            <label style="color:#777;margin-bottom:1px;font-size:13px;"  class="ttg__required">{{ __('E-Mail Address') }}</label>
                            <input style="color:#777!important;" id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>
                            @if ($errors->has('email'))
                                <span class="invalid-feedback errors">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-md-8 offset-md-2 col-10 offset-1">
                            <label style="color:#777;margin-bottom:1px;font-size:13px;"  class="ttg__required">{{ __('Password') }}</label>
                            <input style="color:#777!important;" id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                            @if ($errors->has('password'))
                                <span class="invalid-feedback errors">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-md-8 offset-md-2 col-10 offset-1">
                            <label style="color:#777;margin-bottom:1px;font-size:13px;"  class="ttg__required">{{ __('Confirm Password') }}</label>
                            <input style="color:#777!important;" id="password-confirm" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password_confirmation" required>
                            @if ($errors->has('password'))
                                <span class="invalid-feedback errors">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 offset-md-2 col-10 offset-1">
                            <button type="submit" class="btn" style="width:100%;display:block;font-size:13px;"> {{ __('Register') }}</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 offset-md-2 col-10 offset-1" style="text-align:center;">
                            <label style="color:#abaaaa; font-size:11px;margin-bottom:1px;"> Already a member? <a href="/login" style="color:#abaaaa; text-decoration:underline;">Login now</a></label>
                            <label style="color:#abaaaa; font-size:11px;"> <a href="/" style="color:#abaaaa; text-decoration:underline;">Take me Home</a></label>
                        </div>
                    </div>
                </label>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
