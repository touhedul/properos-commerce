@extends('themes.'.(isset($theme)?$theme:'default').'.layouts.main')

@section('meta_specific')
    @if(isset($page->m_title) && $page->m_title != '')
        <meta property="og:title" content="{{$page->m_title}}">
    @else
        <meta property="og:title" content="{{$page->p_title}}">
    @endif

    @if(isset($page->m_description) && $page->m_description != '')
        <meta property="og:description" content="{{$page->m_description}}">
    @endif

    <meta name="keywords" content="{{($page->m_keywords != null && $page->m_keywords != '') ? $page->m_keywords: $keywords}}"/>

    <meta property="og:site_name" content="{{env('APP_NAME')}}">
    <meta property="og:type" content="website" />

    @if(isset($page->m_url) && $page->m_url != '')
        <meta property="og:url" content="{{url('/pages/' . $page->m_url)}}" >
    @endif

    @if(isset($page->m_title) && $page->m_title != '')
        <meta  name="twitter:title" content="{{$page->m_title}}">
    @else
        <meta  name="twitter:title" content="{{$page->p_title}}">
    @endif

    @if(isset($page->m_description) && $page->m_description != '')
        <meta name="twitter:description"  content="{{$page->m_description}}">
    @endif
    @if(isset($page->header_media_path))
        <meta property="og:image" content="{{url(env('APP_CDN') . '/' . $page->header_media_path)}}">
        <meta name="twitter:image" content="{{url(env('APP_CDN') . '/' . $page->header_media_path)}}">
        <meta name="twitter:card" content="summary_large_image">
    @endif
@endsection

@section('local_styles')
<style>
    .tt-about__info div,
    .tt-about__info p {
        max-width: 820px;
    }

    #theme .tt-about__info {
        background-color: #fff;
    }

    .header-image {
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-position: center;
        background-size: cover;
        width: 120%;
        height: 500px;
        width: 950%;
        opacity: 0.9
    }
</style>
@endsection

@section('content')
<div class="tt-layout tt-sticky-block__parent tt-layout__fullwidth">
    <div class="tt-layout__content">
        <div class="container">
            <div class="tt-about" style="margin-bottom: 0px; margin-top: 0px;">
                <div class="row" style="margin-left: 0px; margin-right: 0px;">
                    @if(isset($page->header_media_path) && $page->header_media_path != null)
                        <div class="header-image" style="background-image: url('/storage/{{$page->header_media_path}}');">

                        </div>
                    @endif
                </div>
                <div class="row ttg-grid-padding--none">
                    <div class="col-md-12">
                        <div class="container-fluid ttg-cont-padding--none">
                            <div class="col-12 col-md-10 offset-md-1" style="padding: 0 25px 25px 25px;">
                                    {!!$page->p_content!!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('local_script')
@endsection
