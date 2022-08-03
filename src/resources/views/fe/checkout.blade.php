@extends('themes.'.(isset($theme)?$theme:'default').'.layouts.main')
@section('local_styles')
<link rel="stylesheet" type="text/css" href="/be/app-assets/vendors/css/forms/selects/select2.min.css">
<link rel="stylesheet" type="text/css" href="/be/app-assets/vendors/css/forms/icheck/icheck.css">
<link rel="stylesheet" type="text/css" href="/be/app-assets/vendors/css/extensions/sweetalert.css">
<style>
.tt-checkout {
	padding:15px;
	margin-top: 30px;
}
</style>
@endsection

@section('content')
  <div id="commerce-module">
    @if(strtolower(env('CARD_PROCESSOR')) == 'authorize') 
      <checkout
        :items="{{$content['cart']['cart'] ? json_encode($content['cart']['cart']) : json_encode("")}}"
        :torder="{{isset($content['order']) ? json_encode($content['order']) : json_encode("")}}"
        :discount="{{isset($content['discount']) ? json_encode($content['discount']) : json_encode("")}}"
        :payment_methods="{{$payment_methods ? json_encode($payment_methods) : json_encode("")}}"
        :account="{{isset($account) ? json_encode($account) : json_encode("")}}" :card_processor="{{json_encode(env('CARD_PROCESSOR'))}}"
        :available_shipping_methods="{{isset($available_shipping_methods) ? json_encode($available_shipping_methods) : json_encode("")}}"
        :client_key="'{{env('AUTHORIZE_PUBLIC_KEY')}}'" :api_id="'{{env('AUTHORIZE_API_ID')}}'" :paypal="'{{env('PAYPAL')}}'"
        :shipping_integration="'{{isset($shipping_integration) ? $shipping_integration : false}}'"
        :codes="{{json_encode(config('shipping.ups.services_allowed'))}}"
      >
      </checkout>
    @elseif(strtolower(env('CARD_PROCESSOR')) == 'stripe')
      <checkout
        :items="{{$content['cart']['cart'] ? json_encode($content['cart']['cart']) : json_encode("")}}"
        :torder="{{isset($content['order']) ? json_encode($content['order']) : json_encode("")}}"
        :discount="{{isset($content['discount']) ? json_encode($content['discount']) : json_encode("")}}"
        :payment_methods="{{$payment_methods ? json_encode($payment_methods) : json_encode("")}}"
        :account="{{isset($account) ? json_encode($account) : json_encode("")}}" :card_processor="{{json_encode(env('CARD_PROCESSOR'))}}"
        :available_shipping_methods="{{isset($available_shipping_methods) ? json_encode($available_shipping_methods) : json_encode("")}}"
        :client_key="'{{env('STRIPE_PUBLIC_KEY')}}'" :paypal="'{{env('PAYPAL')}}'"
        :codes="{{json_encode(config('shipping.ups.services_allowed'))}}"
      >
      </checkout>
    @endif
  </div>
@endsection
@section('local_script')
<script src="/be/app-assets/vendors/js/forms/icheck/icheck.min.js" type="text/javascript"></script>
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