@extends('themes.'.(isset($theme)?$theme:'default').'.layouts.main')
@section('local_styles')
<link rel="stylesheet" type="text/css" href="/be/app-assets/vendors/css/extensions/sweetalert.css">
@endsection
@section('content')
<div id="commerce-module">
    <order-confirm
        :order="{{$order ? json_encode($order) : json_encode('')}}"
        :payment_error="{{isset($payment_error) ? json_encode($payment_error) : json_encode('')}}"
        :user="{{Auth::check() ? json_encode(Auth::user()) : json_encode('')}}"
        :status="{{$status ? json_encode($status) : json_encode('')}}"
    ></order-confirm>
</div>
@endsection
@section('local_script')
<script src="/be/app-assets/vendors/js/extensions/sweetalert.min.js" type="text/javascript"></script>
{{-- <script src="{{ asset('fe/js/modules/commerce.js') }}"></script> --}}
@endsection