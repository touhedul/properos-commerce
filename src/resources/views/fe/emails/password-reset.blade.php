@extends('themes.'.(isset($theme)?$theme:'default').'.layouts.email') 
@section('local-css')
<style>
a {
    background-color: #13919b;
    color: white;
    padding: 1em 1.5em;
    text-decoration: none;
    text-transform: uppercase;
  }
</style>
@endsection
@section('content')
<table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="#ffffff" style="margin-bottom: 0px;">
    <tr>
        <td style="padding: 40px; padding-bottom: 0px; font-family: sans-serif; font-size: 20px; line-height: 27px; color: #666666; font-size: 16px">
            <strong>Hi {{$user->firstname}} {{$user->lastname}},</strong>
        </td>
    </tr>
    <tr>
        <td style="padding: 40px; padding-top: 10px; font-family: sans-serif; font-size: 20px; line-height: 27px; color: #666666; font-size: 14px; text-align: left">
            You are receiving this email because we received a password reset request for your account. If you made this request, click the button below to reset your
            password, otherwise contact us at <strong style="color: green; font-size: 14px">{{env('MAIL_FROM_ADDRESS', 'support@properos.com')}}</strong> or call to <strong style="color: green; font-size: 14px">{{env('APP_PHONE')}}</strong> in order to report this issue.
            <br>
        </td>
    </tr>
    <tr>
        <td style="padding: 40px; padding-top: 10px; font-family: sans-serif; font-size: 22px; line-height: 27px; color: #666666; font-size: 14px; text-align: center">
            <div style="text-align: center">
                <a href="{{url(config('app.url').route('password.reset', $token, false))}}"><b>Reset your password here</b></a>
            </div>
        </td>
    </tr>
    <tr>
        <td style="padding: 40px; padding-top: 10px; font-family: sans-serif; line-height: 27px; color: #666666; font-size: 14px; text-align: justify">
            <div style="text-align: right; color: green; font-size: 14px"><b>Thank you for using {{env('APP_NAME', 'Properos')}} !!!</b></div>
        </td>
    </tr>
    <tr>
        <td style="padding: 40px; padding-top: 10px; font-family: sans-serif; line-height: 27px; color: #666666; font-size: 14px; text-align: justify">

        </td>
    </tr>
</table>
@endsection