@extends('themes.'.(isset($theme)?$theme:'default').'.layouts.main') 
@section('local_styles')
<style>
.tt-cart {
	padding:15px;
	margin-top: 30px;
}
</style>
@endsection
@section('content')
<div id="commerce-module">
    <cart
        :items="{{$content['cart']['cart'] ? json_encode($content['cart']['cart']) : json_encode('')}}"
        :user="{{Auth::check() ? json_encode(Auth::user()) : json_encode('')}}"
    ></cart>
</div>
@endsection
@section('local_script')
{{-- <script src="{{ asset('fe/js/modules/commerce.js') }}"></script> --}}
@endsection