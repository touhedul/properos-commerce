@extends('be.layouts.main') 
@section('specific_vendor_header')
<link rel="stylesheet" type="text/css" href="/be/app-assets/vendors/css/forms/icheck/icheck.css">
<link rel="stylesheet" type="text/css" href="/be/app-assets/vendors/css/extensions/sweetalert.css">
@endsection

@section('local-styles')

<style>
    .header-navbar {
        box-shadow: 0 10px 40px 0 rgba(62, 57, 107, 0.07), 0 2px 9px 0 rgba(62, 57, 107, 0.06);
    }

    .breadcrumb {
        float: right;
    }
</style>
@endsection
 
@section('content')
<div class="content-header row">
    <div class="content-header col-md-12 col-12 mb-2">
        <div class="content-header-right breadcrumbs-right breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    {{-- <li class="breadcrumb-item"><a href="/admin/store/orders">orders</a>
                    </li> --}}
                    <li class="breadcrumb-item"><a href="/admin/store/edit-order/{{$order_id}}">back</a>
                    </li>
                    <li class="breadcrumb-item">payment history
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div id="commerce-module">
    <payment-history :payments="{{isset($payments) ? json_encode($payments) : json_encode('[]')}}" :order_id="{{isset($order_id) ? json_encode($order_id) : json_encode('[]')}}"></payment-history>
</div>
@endsection
 
@section('specific_vendor_footer')
<script src="/be/app-assets/vendors/js/forms/icheck/icheck.min.js" type="text/javascript"></script>
<script src="/be/app-assets/vendors/js/extensions/sweetalert.min.js" type="text/javascript"></script>
{{-- <script src="/vendor/jquery-cc-vidator/jquery.creditCardValidator.js"></script> --}}
@endsection
 
@section('local_script')
<script>
    $(document).ready(function () {
                $(".nav-item").removeClass("active");
                $('#orders').addClass('active')
            });

</script>
@if(strtolower(env('CARD_PROCESSOR')) == 'authorize') @if(strtolower(env('AUTHORIZE_ENV')) == 'production')
<script type="text/javascript" src="https://js.authorize.net/v1/Accept.js" charset="utf-8"></script>
@else
<script type="text/javascript" src="https://jstest.authorize.net/v1/Accept.js" charset="utf-8"></script>
@endif @elseif(strtolower(env('CARD_PROCESSOR')) == 'stripe')
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
@endif
<script src="{{ asset('be/js/modules/commerce.js') }}"></script>
@endsection