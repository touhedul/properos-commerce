@extends('themes.'.(isset($theme)?$theme:'default').'.layouts.main')
@section('local_styles')
@endsection
 
@section('content')
<div class="tt-layout tt-sticky-block__parent tt-layout__fullwidth">
    <div class="tt-layout__content">
        <div class="container">
            <div class="tt-page__breadcrumbs">
                <ul class="tt-breadcrumbs">
                    <li><a href="/"><i class="icon-home"></i></a></li>
                    <li><a href="/engage-us">Engage Us</a></li>
                </ul>
            </div>

            <div class="text-center">
                <h3>Engage Us</h3>
                <p>Coming soon :)</p>
            </div>
            <script>
                require(['app'], function () {
                        require(['modules/sliderBlog']);
                    });
            </script>
        </div>
    </div>
</div>
@endsection
 
@section('local_script')
@endsection