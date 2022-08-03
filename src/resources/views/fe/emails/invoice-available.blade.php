@extends('themes.'.(isset($theme)?$theme:'properos').'.layouts.email') 
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
        <strong>Hi {{$user->firstname}} {{($user->lastname != null)?$user->lastname:''}},</strong>
        </td>
    </tr>
    <tr>
        <td style="padding: 40px; padding-top: 10px; font-family: sans-serif; font-size: 20px; line-height: 27px; color: #666666; font-size: 14px; text-align: justify">
            Your invoice is already available.</a> 
            <br>
        </td>
    </tr>
    <tr>
        <td style="padding: 40px; padding-top: 10px; font-family: sans-serif; font-size: 22px; line-height: 27px; color: #666666; font-size: 14px; text-align: center">
            <div style="text-align: center">
                <a href="{{env('APP_URL')}}/invoice/{{$order->token}}"><b>View Invoice</b></a>
            </div>
        </td>
    </tr>
    <tr>
        <td style="padding: 40px; padding-top: 10px; font-family: sans-serif; font-size: 20px; line-height: 27px; color: #666666; font-size: 14px; text-align: justify">
            Thank you, {{env('APP_NAME', 'Properos')}}
            <br>
        </td>
    </tr>
    <tr>
        <td style="padding: 40px; padding-top: 10px; font-family: sans-serif; font-size: 14px; line-height: 27px; color: #666666; font-size: 14px; text-align: justify">
            Use this link if button doen't work, <a href="{{env('APP_URL')}}/invoice/{{$order->token}}" style="background-color: #fff!important; color:blue; padding: 0;">{{env('APP_URL')}}/invoice/{{$order->token}}</a> 
            <br>
        </td>
    </tr>
</table>
@endsection