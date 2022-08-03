@extends('themes.'.(isset($theme)?$theme:'default').'.layouts.main') 

@section('meta_specific')
    <meta name="keywords" content="{{($post['keywords'] != null && $post['keywords'] != '') ? $post['keywords']: $keywords}}"/>
    <meta property="og:title" content="{{$post['title']}}">
    <meta property="og:description" content="{{$post['summary']}}">
    <meta property="og:site_name" content="{{env('APP_NAME')}}">
    <meta property="og:type" content="post" />
    <meta property="og:url" content="{{url('/blog/post/' . $post['slug'])}}" />
    <meta name="twitter:title" content="{{$post['title']}}">
    <meta name="twitter:description" content="{{$post['summary']}}">
    @if($post['header_media_type'] == 'picture') 
        <meta property="og:image" content="{{url(env('APP_CDN') . '/' . $post['header_media_path'])}}">
        <meta name="twitter:image" content="{{url(env('APP_CDN') . '/' . $post['header_media_path'])}}">
        <meta name="twitter:card" content="summary_large_image">
    @endif
@endsection

@section('local_styles')
@endsection
 
@section('content')
<div id="cms-module" class="content">
    <post-details
    :post="{{(isset($post)) ? $post : 'null'}}"
    :current_user="{{(isset($current_user)) ? $current_user : 'null'}}">
    </post-details>
</div>
@endsection
 
@section('local_script')
<script src="{{ asset('fe/js/modules/cms.js') }}"></script>
@endsection