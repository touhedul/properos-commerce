@extends('themes.'.(isset($theme)?$theme:'properos').'.layouts.email') 

@section('local-css')
<style>
    /* reset */


/* table */

.inventory, .balance { font-size: 75%; table-layout: fixed; width: 100%; }
.inventory, .balance { border-collapse: separate; border-spacing: 2px; }
/* th, td { border-width: 1px; padding: 0.5em; position: relative; text-align: left; }
th, td { border-radius: 0.25em; border-style: solid; }
th { background: #EEE; border-color: #BBB; }
td { border-color: #DDD; } */

/* page */

/* html { font: 16px/1 'Open Sans', sans-serif; overflow: auto; padding: 0.5in; }
html { background: #999; cursor: default; }

body { box-sizing: border-box; height: 11in; margin: 0 auto; overflow: hidden; padding: 0.5in; width: 8.5in; }
body { background: #FFF; border-radius: 1px; box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5); } */

/* header */

header { margin: 0 0 3em; }
header:after { clear: both; content: ""; display: table; }

header h1 { background: #000; border-radius: 0.25em; color: #FFF; margin: 0 0 1em; padding: 0.5em 0; }
header address { float: left; font-size: 75%; font-style: normal; line-height: 1.25; margin: 0 1em 1em 0; }
header address p { margin: 0 0 0.25em; }
header span, header img { display: block; float: right; }
header span { margin: 0 0 1em 1em; max-height: 25%; max-width: 60%; position: relative; }
header img { max-height: 100%; max-width: 100%; }
header input { cursor: pointer; -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)"; height: 100%; left: 0; opacity: 0; position: absolute; top: 0; width: 100%; }

/* article */

article, article address, table.meta, table.inventory { margin: 0 0 3em; }
article:after { clear: both; content: ""; display: table; }
article h1 { clip: rect(0 0 0 0); position: absolute; }

article address { float: left; font-size: 100%; line-height:120%;font-weight: normal; }

/* table meta & balance */

table.meta, table.balance { float: right; width: 46%; }
table.meta:after, table.balance:after { clear: both; content: ""; display: table; }

/* table meta */

table.meta th { width: 40%; }
table.meta td { width: 60%; }

/* table items */

table.inventory { clear: both; width: 100%; }
table.inventory th { font-weight: bold; text-align: center; }

table.inventory td:nth-child(1) { width: 26%; }
table.inventory td:nth-child(2) { width: 38%; }
table.inventory td:nth-child(3) { text-align: right; width: 12%; }
table.inventory td:nth-child(4) { text-align: right; width: 12%; }
table.inventory td:nth-child(5) { text-align: right; width: 12%; }


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
            This is a copy of your <strong>invoice</strong> PSD-582, click the button below to open it. If you have any issue clicking the button, you can also copy and paste this link in your browser </br>
            <a href="{{env('APP_URL').'/invoice/'. $order_data->order->token}}"
                style="text-decoration:none;">
               <b>{{env('APP_URL').'/invoice/'. $order_data->order->token}}</b>
           </a>
            <br>
        </td>
    </tr>
    <tr>
        <td style="padding: 40px; padding-top: 10px; font-family: sans-serif; font-size: 22px; line-height: 27px; color: #666666; font-size: 14px; text-align: center">
            <div style="font-size: 18px">Order number: <strong style="color: green; font-size: 18px">{{$order_data->order->order_number}}</strong></div>
        </td>
    </tr>
    {{-- <tr>
        <td style="padding: 40px; padding-top: 10px; font-family: sans-serif; font-size: 22px; line-height: 27px; color: #666666; font-size: 14px; text-align: right">
            <div style="font-size: 18px">Total: <strong style="color: #000; font-size: 22px">${{number_format($order_data->order->total_amount,2)}}</strong></div>
        </td>
    </tr> --}}
    <tr>
        <td style="padding: 40px; padding-top: 10px; font-family: sans-serif; font-size: 22px; line-height: 27px; color: #666666; font-size: 14px; text-align: center">
            <a href="{{env('APP_URL').'/invoice/'. $order_data->order->token}}"
                 style="padding: 20px; border-radius: 30px; border-width: 1px; border-style: solid; text-decoration: none; color: #fff; background-color: green; width: 50%">
                <b>OPEN INVOICE</b>
            </a>
        </td>
    </tr>
    @if($order_data->order->shipping_address1)
    <tr>
        <td style="padding: 40px; padding-top: 10px; font-family: sans-serif; line-height: 27px; color: #666666; font-size: 14px; text-align: justify">
            <h3 style="margin-bottom:0px;">Shipping address: </h3>
            <div style="font-size: 16px">
                {{$order_data->order->shipping_address1}} {{ $order_data->order->shipping_address2}} {{ $order_data->order->shipping_city}}
                {{ $order_data->order->shipping_zip}} {{ $order_data->order->shipping_state}} {{ $order_data->order->shipping_country}}
            </div>
        </td>
    </tr>
    @endif
    <tr>
        <td style="padding: 40px; padding-top: 10px; font-family: sans-serif; font-size: 20px; line-height: 27px; color: #666666; font-size: 14px; text-align: justify">
            <h3>Order Summary</h3>
            {{-- <table style="margin-top: 20px">
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
                        @if(isset($orderItem->item_with_image) && isset($orderItem->item_with_image->files))
                            @if( count($orderItem->item_with_image->files) > 0)
                            <img src="{{$message->embed((isset($orderItem->item_with_image->files[0]->thumb_path)) ? env('APP_CDN').'/'. $orderItem->item_with_image->files[0]->thumb_path : public_path().'/images/item-placeholder.jpg')}}" style="width: 50px; height: auto">
                            @else
                            <img src="{{$message->embed(public_path().'/images/item-placeholder.jpg')}}" style="width: 50px; height: auto">
                            @endif
                        @else
                        <img src="{{$message->embed(public_path().'/images/icons/subscriptions.png')}}" style="width: 50px; height: auto">
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
              
            </table> --}}
            <table class="inventory">
                <thead>
                    <tr>
                        <th style="border-width: 1px; padding: 0.5em; position: relative; text-align: left;border-radius: 0.25em; border-style: solid;background: #EEE; border-color: #BBB; "><span>Item</span></th>
                        <th style="border-width: 1px; padding: 0.5em; position: relative; text-align: left;border-radius: 0.25em; border-style: solid;background: #EEE; border-color: #BBB; "><span>Description</span></th>
                        <th style="border-width: 1px; padding: 0.5em; position: relative; text-align: left;border-radius: 0.25em; border-style: solid;background: #EEE; border-color: #BBB; "><span>Price </span></th>
                        <th style="border-width: 1px; padding: 0.5em; position: relative; text-align: left;border-radius: 0.25em; border-style: solid;background: #EEE; border-color: #BBB; "><span>Quantity</span></th>
                        <th style="border-width: 1px; padding: 0.5em; position: relative; text-align: left;border-radius: 0.25em; border-style: solid;background: #EEE; border-color: #BBB; "><span>Subtotal </span></th>
                    </tr>
                </thead>
                <tbody id="invoice_body">
                    @foreach($order_data->order->orderItems as $order_item)
                    <tr>
                        <td style="border-width: 1px; padding: 0.5em; position: relative; text-align: left;border-radius: 0.25em; border-style: solid;border-color: #DDD; ">
                            <div>
                            <span>
                            @if(isset($orderItem->item_with_image) && isset($orderItem->item_with_image->files) && count($orderItem->item_with_image->files) > 0)
                                {{-- @if( count($orderItem->item_with_image->files) > 0) --}}
                                <img src="{{$message->embed((isset($orderItem->item_with_image->files[0]->thumb_path)) ? env('APP_CDN').'/'. $orderItem->item_with_image->files[0]->thumb_path : public_path().'/images/item-placeholder.jpg')}}" style="width: 40px; height: auto">
                            @else
                                <img src="{{$message->embed(public_path().'/images/item-placeholder.jpg')}}" style="width: 40px; height: auto">
                            @endif
                        </span><p style="margin:0px;">
                                {{ $order_item->sku }}
                                </p></div></td>
                        <td style="border-width: 1px; padding: 0.5em; position: relative; text-align: left;border-radius: 0.25em; border-style: solid;border-color: #DDD; "><span>{{ $order_item->name }}</span></td>
                        @if($order_item->discount_percent > 0)
                            <td style="border-width: 1px; padding: 0.5em; position: relative; text-align: right;border-radius: 0.25em; border-style: solid;border-color: #DDD; "><span data-prefix="">$</span><span>{{number_format(($order_item->price - ($order_item->price*$order_item->discount_percent/100)),2)}}</span></td>
                        @else
                            <td style="border-width: 1px; padding: 0.5em; position: relative; text-align: right;border-radius: 0.25em; border-style: solid;border-color: #DDD; "><span data-prefix="">$</span><span>{{number_format($order_item->price,2)}}</span></td>
                        @endif
                        <td style="border-width: 1px; padding: 0.5em; position: relative; text-align: right;border-radius: 0.25em; border-style: solid;border-color: #DDD; "><span>{{($order_item->qty % 1 != 0) ? $order_item->qty : round($order_item->qty,2) }}</span></td>
                        <td style="border-width: 1px; padding: 0.5em; position: relative; text-align: right;border-radius: 0.25em; border-style: solid;border-color: #DDD; "><span data-prefix="">$</span><span>{{number_format((($order_item->price - ($order_item->price*$order_item->discount_percent/100)) * $order_item->qty),2)}}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <table class="balance">
                <tbody>
                    <tr>
                        <th style="border-width: 1px; padding: 0.5em; position: relative; text-align: left;border-radius: 0.25em; border-style: solid;background: #EEE; border-color: #BBB; "><span contenteditable="">Items Subtotal</span></th>
                        <td style="border-width: 1px; padding: 0.5em; position: relative; text-align: right;border-radius: 0.25em; border-style: solid;border-color: #DDD; "><span data-prefix="">$ <span contenteditable="">{{ number_format($order_data->order->subtotal,2) }}</span></span></td>
                    </tr>
                    @if($order_data->order->discount_amount > 0)
                        <tr>
                            <th style="border-width: 1px; padding: 0.5em; position: relative; text-align: left;border-radius: 0.25em; border-style: solid;background: #EEE; border-color: #BBB; "><span contenteditable="">Discount({{$order_data->order->discount_code}})</span></th>
                        <td style="border-width: 1px; padding: 0.5em; position: relative; text-align: right;border-radius: 0.25em; border-style: solid;border-color: #DDD; "><span data-prefix="">$ <span contenteditable="">{{ number_format($order_data->order->discount_amount,2) }}</span></span></td>
                        </tr>
                    @endif
                    @if($order_data->order->shipping_cost_estimated > 0)
                    <tr>
                        <th style="border-width: 1px; padding: 0.5em; position: relative; text-align: left;border-radius: 0.25em; border-style: solid;background: #EEE; border-color: #BBB; "><span contenteditable="">Shipping & Handling</span></th>
                        <td style="border-width: 1px; padding: 0.5em; position: relative; text-align: right;border-radius: 0.25em; border-style: solid;border-color: #DDD; "><span data-prefix="">$ <span contenteditable="">{{ number_format($order_data->order->shipping_cost_estimated,2) }}</span></span></td>
                    </tr>
                    @endif
                    @if($order_data->order->total_tax_amount > 0)
                    <tr>
                        <th style="border-width: 1px; padding: 0.5em; position: relative; text-align: left;border-radius: 0.25em; border-style: solid;background: #EEE; border-color: #BBB; "><span contenteditable="">Total Before Tax</span></th>
                        <td style="border-width: 1px; padding: 0.5em; position: relative; text-align: right;border-radius: 0.25em; border-style: solid;border-color: #DDD; "><span data-prefix="">$ <span contenteditable="">{{ number_format($order_data->order->subtotal-$order_data->order->discount_amount+$order_data->order->shipping_cost_estimated,2) }}</span></span></td>
                    </tr>
                    @endif
                    @if($order_data->order->total_tax_amount > 0)
                    <tr>
                        <th style="border-width: 1px; padding: 0.5em; position: relative; text-align: left;border-radius: 0.25em; border-style: solid;background: #EEE; border-color: #BBB; "><span contenteditable="">Taxes</span></th>
                        <td style="border-width: 1px; padding: 0.5em; position: relative; text-align: right;border-radius: 0.25em; border-style: solid;border-color: #DDD; "><span data-prefix="">$ <span contenteditable="">{{ number_format($order_data->order->total_tax_amount,2) }}</span></span></td>
                    </tr>
                    @endif
                    <tr>
                        <th style="border-width: 1px; padding: 0.5em; position: relative; text-align: left;border-radius: 0.25em; border-style: solid;background: #EEE; border-color: #BBB; "><span contenteditable="">Order Total</span></th>
                        <td style="border-width: 1px; padding: 0.5em; position: relative; text-align: right;border-radius: 0.25em; border-style: solid;border-color: #DDD; "><span data-prefix="">$</span><span contenteditable="">{{ number_format($order_data->order->total_amount,2) }}</span></td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>  
    
</table>
@endsection