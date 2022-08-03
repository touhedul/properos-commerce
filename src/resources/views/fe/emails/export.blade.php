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
            {{$user}}
        </td>
    </tr>
    <tr>
        <td style="padding: 40px; padding-top: 10px; font-family: sans-serif; font-size: 20px; line-height: 27px; color: #666666; font-size: 14px; text-align: justify">
                Find below the files from you recent export @if($filename != "Search Result"){{$filename}}@endif in {{env('APP_NAME',' our store')}}.
            <br>
        </td>
    </tr>
        <tr>
            <td style="padding: 40px; padding-top: 10px; font-family: sans-serif; font-size: 20px; line-height: 27px; color: #666666; font-size: 14px; text-align: justify">
                {{-- <b>Customer Name:</b> {{$email_data->name}}<br>
                <b>Customer Email:</b> {{$email_data->from}}<br>
                <b>Customer Phone:</b> {{$email_data->phone ? $email_data->phone : 'Not available'}}<br><br>
                <b>Message:</b> {{$email_data->message}} --}}
                @if(count($urls)>1)
                    @foreach ($urls as $url)
                    <a href="{{$url}}">{{$url}}</a><br>
                    @endforeach
                @elseif(count($urls)>0)
                    <a href="{{$urls[0]}}">{{$urls[0]}}</a>
                @endif
            </td>
        </tr>
</table>
@endsection