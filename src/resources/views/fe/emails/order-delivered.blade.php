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
            Your order with number <strong style="color: green; font-size: 18px">{{$order_data->order->order_number}}</strong> has been delivered. 
            <br>
        </td>
    </tr>
    <tr>
        <td style="padding: 40px; padding-top: 10px; font-family: sans-serif; font-size: 20px; line-height: 27px; color: #666666; font-size: 14px; text-align: justify">
            <h3>Order Summary</h3>
            <table style="margin-top: 20px">
                <tr>
                    <th>
                        Product
                    </th>
                    <th>
                        Quantity
                    </th>
                    <th>
                        Price/Unit
                    </th>
                </tr>
                @foreach($order_data->order->orderItems as $key =>$orderItem)
                <tr style="border-top: 1px solid #d1d3d6">
                    <td style="padding: 15px; width: 33.3%;">
                        @if(count($orderItem->item_with_image->files) > 0)
                        <img src="{{$message->embed((isset($orderItem->item_with_image->files[0]->thumb_path)) ? env('APP_CDN').'/'. $orderItem->item_with_image->files[0]->thumb_path : public_path().'/images/item-placeholder.jpg')}}" style="width: 150px; height: auto">
                        @else
                        <img src="{{$message->embed(public_path().'/images/item-placeholder.jpg')}}" style="width: 150px; height: auto">
                        @endif
                    </td>
                    <td style="padding: 15px; width: 33.3%">
                        <span style="font-size: 18px"><b>{{($orderItem->qty % 1 != 0) ? $orderItem->qty : number_format($orderItem->qty,0)}}</b></span>
                    </td>
                    <td style="padding: 15px; width: 33.3%">
                        @if($orderItem->discount_percent > 0)
                            <span style="font-size: 16px"><b>${{number_format($orderItem->price - ($orderItem->price * $orderItem->discount_percent/100),2)}}</b></span>
                            <span style="font-size: 12px;color:red;"><b>({{($orderItem->discount_percent  % 1 != 0) ? $orderItem->discount_percent : number_format($orderItem->discount_percent,0)}}% off)</b></span>
                        @else
                            <span style="font-size: 16px"><b>${{number_format($orderItem->price,2)}}</b></span>
                        @endif
                    </td>
                    </tr>
                @endforeach
                
            </table>
        </td>
    </tr>
    {{-- @if($order_data->order->discount_amount != null && $order_data->order->discount_amount != 0)
        <tr>
            <td style="padding: 40px; padding-top: 10px; padding-bottom: 15px;font-family: sans-serif; font-size: 16px; line-height: 27px; color: #666666; font-size: 14px; text-align: right">
                    <div style="font-size: 18px"><strong style="color: #000; font-size: 16px">Coupon: </strong>{{$order_data->order->discount_code}}</div>
            </td>
        </tr>
    @endif --}}
    <tr>
        <td style="padding: 40px; padding-top: 10px; font-family: sans-serif; font-size: 16px; line-height: 27px; color: #666666; font-size: 14px; text-align: right">
            <div style="font-size: 18px">Items Subtotal: <strong style="color: #000; font-size: 16px">${{number_format((($order_data->order->total_amount - $order_data->order->total_tax_amount - $order_data->order->shipping_cost_estimated) + $order_data->order->discount_amount),2)}}</strong></div>
            @if($order_data->order->discount_amount != null && $order_data->order->discount_amount != 0)
                <div style="font-size: 18px">Discount({{$order_data->order->discount_code}}): <strong style="color: #000; font-size: 16px">${{number_format($order_data->order->discount_amount,2)}}</strong></div>
            @endif
            <div style="font-size: 18px">Shipping & Handling: <strong style="color: #000; font-size: 16px">${{number_format($order_data->order->shipping_cost_estimated,2)}}</strong></div>
            <div style="font-size: 18px">Total Before Tax: <strong style="color: #000; font-size: 16px">${{number_format($order_data->order->subtotal+$order_data->order->shipping_cost_estimated-$order_data->order->discount_amount,2)}}</strong></div>
            {{-- @if($order_data->order->total_tax_amount != null && $order_data->order->total_tax_amount > 0) --}}
                <div style="font-size: 18px">Taxes: <strong style="color: #000; font-size: 16px">${{number_format($order_data->order->total_tax_amount,2)}}</strong></div>
            {{-- @endif --}}
            {{-- @if($order_data->order->shipping_cost_estimated != null && $order_data->order->shipping_cost_estimated > 0) --}}
            {{-- @endif --}}
            <div style="font-size: 18px;color: #000;">Total paid: <strong style="color: #000; font-size: 16px">${{$order_data->order->paid_amount}}</strong></div>
        </td>
    </tr>
</table>
@endsection