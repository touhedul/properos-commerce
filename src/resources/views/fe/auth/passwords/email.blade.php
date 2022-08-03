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
            <h4 style="color:#777;">Reset Password</h4>
        </div>
        @if (session('status'))
            <div class="alert alert-success" style="margin-left:8%; margin-right:8%;">
                {{ session('status') }}
            </div>
        @endif
        <form method="POST" action="{{ route('password.email') }}">
         @csrf
            <div class="tt-form" style="padding-bottom:20px;">
                <div class="tt-form__form">
                    <label>
                        <div class="row">
                            <div class="col-md-8 offset-md-2 col-10 offset-1">
                                <label style="color:#777;margin-bottom:1px;font-size:13px;"  class="ttg__required">{{ __('E-Mail Address') }}</label>
                                <input style="color:#777!important;" id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback errors">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8 offset-md-2 col-10 offset-1">
                                <button type="submit" class="btn" style="width:100%;display:block;font-size:13px;"> {{ __('Send Password Reset Link') }}</button>
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
            {{-- <div class="row ttg-mt--40">
                <div class="col-md-9 offset-md-3">
                    <button type="submit" class="btn"> {{ __('Send Password Reset Link') }}</button>
                </div>
            </div> --}}
        </form>
    </div>
</div>
@endsection
