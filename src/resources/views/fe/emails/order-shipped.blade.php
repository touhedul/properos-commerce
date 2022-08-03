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
            <strong>Hi {{$order_data->order->customer_name}},</strong>
        </td>
    </tr>
    <tr>
        <td style="padding: 40px; padding-top: 10px; font-family: sans-serif; font-size: 20px; line-height: 27px; color: #666666; font-size: 14px; text-align: justify">
            Your order with number <strong style="color: green; font-size: 18px">{{$order_data->order->order_number}}</strong> has been shipped. Following is the shipping information you need to track your order, including a direct link to the courier's tracking system.
            <br>
        </td>
    </tr>
    @if($order_data->order->shippingMethod)
    <tr>
        <td style="padding: 40px; padding-top: 10px; font-family: sans-serif; font-size: 22px; line-height: 27px; color: #666666; font-size: 14px; text-align: center">
            <div style="font-size: 18px">Courier: <strong style="color: gray; font-size: 18px">{{$order_data->order->shippingMethod->label}}</strong></div><br>
                @if($order_data->order->shippingMethod->name == 'usps')
                <div><img style="width: 150px; height: auto" src="{{$message->embed(public_path().'/images/carriers/usps.jpg')}}"></div>
                @endif
                @if($order_data->order->shippingMethod->name == 'ups')
                <div><img style="width: 60px; height: auto" src="{{$message->embed(public_path().'/images/carriers/ups.png')}}"></div>
                @endif
                @if($order_data->order->shippingMethod->name == 'fedex')
                <div><img style="width: 150px; height: auto" src="{{$message->embed(public_path().'/images/carriers/fedex.jpg')}}"></div>
                @endif
                @if($order_data->order->shippingMethod->name == 'dhl')
                <div><img style="width: 100px; height: auto" src="{{$message->embed(public_path().'/images/carriers/dhl.jpg')}}"></div>
                @endif
                @if($order_data->order->shippingMethod->name == 'manual')
                <div><img style="width: 100px; height: auto" src="{{$message->embed(public_path().'/images/carriers/manual.jpg')}}"></div>
                @endif
        </td>
    </tr>
    <tr>
        <td style="padding: 40px; padding-top: 10px; font-family: sans-serif; line-height: 27px; color: #666666; font-size: 14px; text-align: justify">
            <h3>Shipping information: </h3>
            <div style="font-size: 16px">
                {{-- <div><span>Carrier: </span><span><b> {{$order_data->order->shippingMethod->label}}</b></span></div><br> --}}
                <div><span>Tracking/Label Number: </span> <span><b> {{$order_data->order->shipping_tracking}}</b></span></div><br><br>
                @if($order_data->order->shippingMethod->name == 'usps')
                <div style="text-align: center"><a href="{{env('USPS_TRACK_URL_LABEL').$order_data->order->shipping_tracking}}">Track your order now</a></div>
                @endif @if($order_data->order->shippingMethod->name == 'ups')
                <div style="text-align: center"><a href="{{env('UPS_TRACK_URL').$order_data->order->shipping_tracking}}">Track your order now</a></div>
                @endif @if($order_data->order->shippingMethod->name == 'fedex')
                <div style="text-align: center"><a href="{{env('FEDEX_TRACK_URL').$order_data->order->shipping_tracking}}">Track your order now</a></div>
                @endif @if($order_data->order->shippingMethod->name == 'dhl')
                <div style="text-align: center"><a href="{{env('DHL_US_TRACK_URL').$order_data->order->shipping_tracking}}">Track your order now</a></div>
                @endif
            </div>
        </td>
    </tr>
    @endif
    <tr>
        <td style="padding: 40px; padding-top: 10px; font-family: sans-serif; line-height: 27px; color: #666666; font-size: 14px; text-align: justify">
            <h3>Shipping address: </h3>
            <div style="font-size: 16px">
                {{$order_data->order->shipping_address1}} {{ $order_data->order->shipping_address2}} {{ $order_data->order->shipping_city}}
                {{ $order_data->order->shipping_zip}} {{ $order_data->order->shipping_state}} {{ $order_data->order->shipping_country}}
            </div>
        </td>
    </tr>
</table>
@endsection