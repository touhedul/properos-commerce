<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr" lang="en">
<head>
  <title>{{env('APP_NAME','Properos')}}</title>
    <link rel="apple-touch-icon" href="/be/app-assets/images/ico/apple-icon-120.png">
  <link rel="shortcut icon" type="image/x-icon" href="/be/app-assets/images/ico/favicon.ico">

  <style>
    @media print {
        div.page-break {
            display: block;
            page-break-before: always;
        }
    }
    .label {
        width: 7in;
        height: 4in;
        margin: 5px;
        padding: 0px;
    }
</style>
</head>
<body>
@if(count($labels) > 0)
@foreach($labels as $label)
    <div class="page-break">
        <div>
            <img src="{{env('APP_CDN').'/'. $label->path}}" class="label">
        </div>
    </div>
@endforeach
@endif

</body>
</html>