@extends('themes.'.(isset($theme)?$theme:'default').'.layouts.main')
@section('local_styles')
    <link rel="stylesheet" type="text/css" href="/be/app-assets/vendors/css/extensions/sweetalert.css">
    <link rel="stylesheet" type="text/css" href="/be/app-assets/vendors/css/forms/selects/select2.min.css">
@endsection
 
@section('content')
<div id="commerce-module">
    @if(strtolower(env('CARD_PROCESSOR')) == 'authorize')
        <my-account 
            :account="{{isset($account) ? json_encode($account) : json_encode('')}}" 
            :orders="{{isset($orders) ? json_encode($orders) : json_encode('')}}" :card_processor="{{json_encode(env('CARD_PROCESSOR'))}}"
            :client_key="{{json_encode(env('AUTHORIZE_PUBLIC_KEY'))}}" :api_id="{{json_encode(env('AUTHORIZE_API_ID'))}}">
        </my-account>
    @elseif(strtolower(env('CARD_PROCESSOR')) == 'stripe')
        <my-account 
            :account="{{isset($account) ? json_encode($account) : json_encode('')}}" 
            :orders="{{isset($orders) ? json_encode($orders) : json_encode('')}}" :card_processor="{{json_encode(env('CARD_PROCESSOR'))}}"
            :client_key="{{json_encode(env('STRIPE_PUBLIC_KEY'))}}" :description="{{json_encode(env('STRIPE_STATEMENT_DESCRIPTOR'))}}">
        </my-account>
    @endif
</div>
@endsection
 
@section('local_script')
<script src="/be/app-assets/vendors/js/extensions/sweetalert.min.js" type="text/javascript"></script>
<script src="/be/app-assets/vendors/js/forms/select/select2.full.min.js" type="text/javascript"></script>
@if(strtolower(env('CARD_PROCESSOR')) == 'authorize')
  @if(strtolower(env('AUTHORIZE_ENV')) == 'production')
  <script type="text/javascript"  src="https://js.authorize.net/v1/Accept.js" charset="utf-8"></script>
  @else
  <script type="text/javascript"  src="https://jstest.authorize.net/v1/Accept.js" charset="utf-8"></script>
  @endif
@elseif(strtolower(env('CARD_PROCESSOR')) == 'stripe')
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
@endif
{{-- <script src="{{ asset('fe/js/modules/commerce.js') }}"></script> --}}
@endsection