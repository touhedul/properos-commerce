@section('meta')
<meta property="og:title" content="{{$item->name}}">
<meta property="og:description" content="{{substr($item->description,0,255)}}">
<meta property="og:site_name" content="{{env('APP_NAME')}}">
<meta property="og:type" content="product" />
<meta property="og:url" content="{{env('APP_URL').'/items/'.$item->sku}}" />
<meta name="twitter:title" content="{{$item->name}}">
<meta name="twitter:description" content="{{substr($item->description,0,255)}}"> 
@if(count($item->files) >0)
<meta property="og:image" content="{{env('APP_CDN').'/'.$item->files[0]->path}}">
{{-- <meta property="og:image:width" content="690" />
<meta property="og:image:height" content="690" /> --}}
<meta name="twitter:image" content="{{env('APP_CDN').'/'.$item->files[0]->path}}"> 
@endif
<meta name="twitter:card" content="summary_large_image"> 
@section('local_styles')
<link rel="stylesheet" type="text/css" href="/be/app-assets/vendors/css/extensions/sweetalert.css">
@endsection

@endsection
 
@extends('themes.'.(isset($theme)?$theme:'default').'.layouts.main')
@section('local_styles')
@endsection
 
@section('content')
<div id="commerce-module">
    <product-details :item="{{$item ? json_encode($item) : json_encode('{}')}}" 
    :similar_items="{{$similar_items ? json_encode($similar_items) : json_encode('{}')}}"
    :host="{{json_encode(env('APP_URL', 'https://properos.com'))}}">
    </product-details>
</div>
@endsection
 
@section('local_script')
<script src="/be/app-assets/vendors/js/extensions/sweetalert.min.js" type="text/javascript"></script>
<div id="fb-root"></div>
{{-- <script async defer crossorigin="anonymous" src="https://connect.facebook.net/es_ES/sdk.js#xfbml=1&version=v3.3"></script> --}}
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js"></script>
{{-- <script src="{{ asset('fe/js/modules/commerce.js') }}"></script> --}}
@endsection