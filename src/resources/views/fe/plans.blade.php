@extends('themes.'.(isset($theme)?$theme:'default').'.layouts.main')
@section('local_styles')
    <link rel="stylesheet" type="text/css" href="/be/app-assets/vendors/css/extensions/sweetalert.css">
    <link rel="stylesheet" type="text/css" href="/be/app-assets/vendors/css/forms/selects/select2.min.css">
@endsection
 
@section('content')
<div id="commerce-module">
    <div class="tt-layout tt-sticky-block__parent ">
        <div class="tt-layout__content">
            <div class="container">
                <div class="tt-page__breadcrumbs">
                    <ul class="tt-breadcrumbs">
                        <li>
                            <a href="index.html">
                                <i class="icon-home"></i>
                            </a>
                        </li>
                        <li>
                            <a href="/my-account">My Account</a>
                        </li>
                    </ul>
                </div>
                @if(strtolower(env('CARD_PROCESSOR')) == 'authorize')
                    <plans 
                        :subscription="{{isset($subscription) ? json_encode($subscription) : json_encode('{}')}}" :card_processor="{{json_encode(env('CARD_PROCESSOR'))}}"
                        :client_key="{{json_encode(env('AUTHORIZE_PUBLIC_KEY'))}}" :api_id="{{json_encode(env('AUTHORIZE_API_ID'))}}">
                    </plans>
                @elseif(strtolower(env('CARD_PROCESSOR')) == 'stripe')
                    <plans 
                        :subscription="{{isset($subscription) ? json_encode($subscription) : json_encode('{}')}}" :card_processor="{{json_encode(env('CARD_PROCESSOR'))}}"
                        :client_key="{{json_encode(env('STRIPE_PUBLIC_KEY'))}}" :description="{{json_encode(env('STRIPE_STATEMENT_DESCRIPTOR'))}}">
                    </plans>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
 
@section('local_script')
<script src="/be/app-assets/vendors/js/extensions/sweetalert.min.js" type="text/javascript"></script>
<script src="/be/app-assets/vendors/js/forms/select/select2.full.min.js" type="text/javascript"></script>
@if(strtolower(env('CARD_PROCESSOR')) == 'authorize')
    <script type="text/javascript"  src="https://jstest.authorize.net/v1/Accept.js" charset="utf-8"></script>
@elseif(strtolower(env('CARD_PROCESSOR')) == 'stripe')
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
@endif
{{-- <script src="{{ asset('fe/js/modules/commerce.js') }}"></script> --}}
@endsection