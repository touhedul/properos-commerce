@extends('be.layouts.main') 
@section('specific_vendor_header')
<link rel="stylesheet" type="text/css" href="/be/app-assets/vendors/css/extensions/sweetalert.css">
<link rel="stylesheet" type="text/css" href="/be/app-assets/vendors/css/printjs/print.min.css">
@endsection
 
 
@section('content')
<div class="content-header row" style="padding: 15px">
    <div class="content-header-left col-md-6 col-12 mb-1">

    </div>
    <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-12">
        <div class="breadcrumb-wrapper col-12" style="padding-bottom: 15px">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin/store/reports">Reports</a>
                </li>
            </ol>
        </div>
    </div>
</div>
<div id="commerce-module">
    <reports-container></reports-container>
</div>
@endsection
 
@section('specific_vendor_footer')
<script src="/be/app-assets/vendors/js/extensions/sweetalert.min.js" type="text/javascript"></script>
<script src="/be/app-assets/vendors/js/printjs/print.min.js" type="text/javascript"></script>
@endsection
 
@section('local_script')
<script>
    $(document).ready(function () {
        $(".nav-item").removeClass("active");
        $('#reports').addClass('active')
    });
</script>
<script src="{{ asset('be/js/modules/commerce.js') }}"></script>
@endsection