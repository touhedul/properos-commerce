@extends('be.layouts.main') 
@section('specific_vendor_header')
<link rel="stylesheet" type="text/css" href="/be/app-assets/vendors/css/extensions/unslider.css">
<link rel="stylesheet" type="text/css" href="/be/app-assets/vendors/css/weather-icons/climacons.min.css">
<link rel="stylesheet" type="text/css" href="/be/app-assets/fonts/meteocons/style.min.css">
<link rel="stylesheet" type="text/css" href="/be/app-assets/vendors/css/charts/morris.css">
@endsection
 
@section('local_styles')
<link rel="stylesheet" type="text/css" href="/be/app-assets/css/core/colors/palette-gradient.min.css">
<link rel="stylesheet" type="text/css" href="/be/app-assets/fonts/simple-line-icons/style.min.css">
<link rel="stylesheet" type="text/css" href="/be/app-assets/css/core/colors/palette-gradient.min.css">
<link rel="stylesheet" type="text/css" href="/be/app-assets/css/pages/timeline.min.css">
<link rel="stylesheet" type="text/css" href="/be/app-assets/css/core/colors/palette-gradient.css">
@endsection
 
@section('content')
<div id="commerce-module">
    <dashboard
        :total_items="{{(isset($totalItems)) ? $totalItems : '""'}}"
        :new_customers="{{(isset($totalCustomers)) ? $totalCustomers : '""'}}"
        :total_profit="{{(isset($totalProfit)) ? $totalProfit : '""'}}"
        :recent_orders="{{($recent_orders) ? $recent_orders : null}}"
        :recent_buyers="{{(isset($recent_buyers)) ? json_encode($recent_buyers) : json_encode('[]')}}"
        :monthly_sales="{{(count($monthly_sales) > 0) ? json_encode($monthly_sales) : json_encode('')}}"
        :popular_products="{{(count($popular_products) > 0) ? json_encode($popular_products) : json_encode('')}}"
        :monthly_orders="{{$monthly_orders}}"
        :active_categories="{{$active_categories}}" :new_orders="{{$new_orders}}"
        >
    </dashboard>
</div>
@endsection
 
@section('specific_vendor_footer')
<script src="/be/app-assets/vendors/js/charts/raphael-min.js" type="text/javascript"></script>
<script src="/be/app-assets/vendors/js/charts/morris.min.js" type="text/javascript"></script>
<script src="/be/app-assets/vendors/js/extensions/unslider-min.js" type="text/javascript"></script>
<script src="/be/app-assets/vendors/js/timeline/horizontal-timeline.js" type="text/javascript"></script>
@endsection
 
@section('local_script')
<script>
    $(document).ready(function () {
        $(".nav-blog").removeClass("active");
        $('#dashboard').addClass('active');
    });
</script>
<script src="{{ asset('be/js/modules/commerce.js') }}"></script>
@endsection