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
        <strong>Hi {{$user->firstname}} {{($user->lastname != null)?$user->lastname:''}},</strong>
        </td>
    </tr>
    <tr>
        <td style="padding: 40px; padding-top: 10px; font-family: sans-serif; font-size: 20px; line-height: 27px; color: #666666; font-size: 14px; text-align: justify">
            We had problems loading the amount owed to your card, please check <a href="{{env('APP_URL')}}/my-account" style="background-color: #fff!important; color:blue; padding: 0;">here</a> that all information is correct and that the account provided has funds.. 
            If the problem persists, do not hesitate to contact us. 
            The subscription will be cancelled if the problem is not solved in the next 7 days after receiving the first email.
            <br>
        </td>
    </tr>
    <tr>
        <td style="padding: 40px; padding-top: 10px; font-family: sans-serif; font-size: 20px; line-height: 27px; color: #666666; font-size: 14px; text-align: justify">
            Thank you, {{env('APP_NAME', 'Properos')}}
            <br>
        </td>
    </tr>
</table>
@endsection