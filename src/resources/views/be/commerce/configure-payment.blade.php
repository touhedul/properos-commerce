@extends('be.layouts.main') 
@section('specific_vendor_header')
<link rel="stylesheet" type="text/css" href="/be/app-assets/vendors/css/forms/icheck/icheck.css">
<link rel="stylesheet" type="text/css" href="/be/app-assets/vendors/css/extensions/sweetalert.css">
<link rel="stylesheet" type="text/css" href="/be/app-assets/vendors/css/forms/selects/select2.min.css">
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
                    <li class="breadcrumb-item"><a href="/admin/store/edit-order/{{$order_id}}">back</a>
                    </li>
                    <li class="breadcrumb-item">payment configuration
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div id="commerce-module">
    <payment-container 
        :user="{{isset($user) ? json_encode($user) : "undefined"}}"
        :order_id="{{isset($order_id) ? json_encode($order_id) : "undefined"}}"
        :total_amount="{{isset($total_amount) ? json_encode($total_amount) : "undefined"}}"
        :card_processor="{{isset($card_processor) ? json_encode($card_processor) : "undefined"}}"
        :client_key="{{isset($client_key) ? json_encode($client_key) : "undefined"}}"
        :api_id="{{isset($api_id) ? json_encode($api_id) : "undefined"}}"
        ></payment-container>
</div>

@endsection
 
@section('specific_vendor_footer')
<script src="/be/app-assets/vendors/js/forms/select/select2.full.min.js" type="text/javascript"></script>
<script src="/be/app-assets/vendors/js/extensions/sweetalert.min.js" type="text/javascript"></script>
<script src="/be/app-assets/vendors/js/forms/icheck/icheck.min.js" type="text/javascript"></script>
@endsection

@section('local_script')
<script src="/vendor/jquery-cc-validator/jquery.creditCardValidator.js"></script>
<script>
    $(document).ready(function () {
                $(".nav-item").removeClass("active");
                $('#orders').addClass('active')
            });

</script>
@if(strtolower(env('CARD_PROCESSOR')) == 'authorize')
@if(strtolower(env('AUTHORIZE_ENV')) == 'production')
<script type="text/javascript"  src="https://js.authorize.net/v1/Accept.js" charset="utf-8"></script>
@else
<script type="text/javascript"  src="https://jstest.authorize.net/v1/Accept.js" charset="utf-8"></script>
@endif
@elseif(strtolower(env('CARD_PROCESSOR')) == 'stripe')
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
@endif
<script src="{{ asset('be/js/modules/commerce.js') }}"></script>
@endsection