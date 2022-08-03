@extends('be.layouts.main') 
@section('specific_vendor_header')
<link rel="stylesheet" type="text/css" href="/be/app-assets/vendors/css/extensions/sweetalert.css">
@endsection
 
@section('local_styles')
@endsection
 
@section('content')
<div class="content-header row" style="padding: 15px">
    <div class="content-header-left col-md-6 col-12 mb-1">

    </div>
    <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-12">
        <div class="breadcrumb-wrapper col-12" style="padding-bottom: 15px">
            <ol class="breadcrumb">
                {{--
                <li class="breadcrumb-item"><a href="index.html">Home</a>
                </li>
                <li class="breadcrumb-item"><a href="#">Starters Kit</a>
                </li>
                <li class="breadcrumb-item active">Dark Layout
                </li> --}}
                <li class="breadcrumb-item">
                    <a href="/admin/store/categories">Categories</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="/admin/store/add-category">Add category</a>
                </li>
            </ol>
        </div>
    </div>
</div>
<div id="commerce-module">
    <categories-list :categories="{{$categories}}"></categories-list>
</div>
@endsection
 
@section('specific_vendor_footer')
<script src="/be/app-assets/vendors/js/extensions/sweetalert.min.js" type="text/javascript"></script>
@endsection
 
@section('local_script')
<script>
    $(document).ready(function () {
        $(".nav-item").removeClass("active");
        $('#categories').addClass('active')
    });
</script>
<script src="{{ asset('be/js/modules/commerce.js') }}"></script>
@endsection