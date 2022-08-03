@extends('themes.'.(isset($theme)?$theme:'default').'.layouts.main')
@section('local_styles')
<link rel="stylesheet" type="text/css" href="/be/app-assets/vendors/css/extensions/sweetalert.css">
<link rel="stylesheet" type="text/css" href="/be/app-assets/vendors/css/forms/selects/select2.min.css">
<style>
#theme .tt-sidebar, #theme .tt-layout__sidebar {
	background-color: #ffffff !important;
}
.tt-layout__sidebar-sticky.tt-sticky-block__inner {
	width: 350px;
}
span.irs-from,span.irs-to {
	background-color: #f5f5f5 !important;
}
span.irs-from::after,span.irs-to::after {
	border-top-color: #f5f5f5 !important;
}
</style>
@endsection
@section('content')
<div id="commerce-module">
    <products-list
        :items='{{isset($items) ? $items : json_encode('')}}'
        :categories="{{isset($categories) ? json_encode($categories) : json_encode('')}}"
        :category="{{isset($category) ? json_encode($category) : json_encode('')}}"
        :sortby="{{isset($sort) ? json_encode($sort) : json_encode('')}}"
        :qoh="{{isset($qoh) ? $qoh : 0}}"
    ></products-list>
</div>
@endsection
@section('local_script')
<script src="/be/app-assets/vendors/js/extensions/sweetalert.min.js" type="text/javascript"></script>
<script src="/be/app-assets/vendors/js/forms/select/select2.full.min.js" type="text/javascript"></script>
{{-- <script src="{{ asset('fe/js/modules/commerce.js') }}"></script> --}}
@endsection