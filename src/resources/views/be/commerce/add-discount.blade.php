@extends('be.layouts.main') 
@section('specific_vendor_header')
<link rel="stylesheet" type="text/css" href="/be/app-assets/vendors/css/forms/selects/select2.min.css">
<link rel="stylesheet" type="text/css" href="/be/app-assets/vendors/css/pickers/datetime/bootstrap-datetimepicker.css">
<link rel="stylesheet" type="text/css" href="/be/app-assets/vendors/css/ui/prism.min.css">
<link rel="stylesheet" type="text/css" href="/be/app-assets/vendors/css/file-uploaders/dropzone.min.css">
<link rel="stylesheet" type="text/css" href="/be/app-assets/vendors/css/extensions/sweetalert.css">
<link rel="stylesheet" type="text/css" href="/be/app-assets/vendors/css/forms/icheck/icheck.css">
<link rel="stylesheet" type="text/css" href="/be/app-assets/css/core/colors/palette-tooltip.css">
@endsection
 
@section('local_styles')
<link rel="stylesheet" type="text/css" href="/be/app-assets/css/plugins/file-uploaders/dropzone.css">
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
                    <a href="/admin/store/discounts">Discounts List</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="/admin/store/add-discount">Add discount</a>
                </li>
            </ol>
        </div>
    </div>
</div>
<div id="commerce-module">
    <discounts-create :editable_discount="{{(isset($editable_discount)) ? $editable_discount : json_encode('')}}"
                >
    </discounts-create>
</div>
@endsection
 
@section('specific_vendor_footer')
<script src="/be/app-assets/vendors/js/pickers/dateTime/moment-with-locales.min.js" type="text/javascript"></script>
<script src="/be/app-assets/vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<script src="/be/app-assets/vendors/js/extensions/dropzone.min.js" type="text/javascript"></script>
<script src="/be/app-assets/vendors/js/ui/prism.min.js" type="text/javascript"></script>
<script src="/be/app-assets/vendors/js/extensions/sweetalert.min.js" type="text/javascript"></script>
<script src="/be/app-assets/vendors/js/forms/icheck/icheck.min.js" type="text/javascript"></script>
<script src="/be/app-assets/vendors/js/forms/select/select2.full.min.js" type="text/javascript"></script>
@endsection
 
@section('local_script')
<script>
    $(document).ready(function () {
        $(".nav-item").removeClass("active");
        $('#discounts').addClass('active')
    });
</script>
<script src="{{ asset('be/js/modules/commerce.js') }}"></script>
@endsection