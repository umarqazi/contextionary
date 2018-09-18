<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <title>@yield('title')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="author" content="">
  {!! HTML::style('assets/css/jquery.mCustomScrollbar.min.css') !!}
  {!! HTML::style('assets/css/bootstrap.min.css') !!}
  {!! HTML::style('assets/css/mdb.min.css') !!}
  {!! HTML::style('assets/css/shards.css') !!}
  {!! HTML::style('assets/css/main.css') !!}
  {!! HTML::style('assets/css/responsive.css') !!}
  {!! HTML::style('assets/css/style.css') !!}
  {!! HTML::style('https://use.fontawesome.com/releases/v5.1.1/css/all.css') !!}
  {!! HTML::style('assets/js/source/jquery.fancybox.css') !!}
  @yield('head_css')
  {{-- End head css --}}
  {!! HTML::script('assets/js/jquery-3.3.1.min.js') !!}
</head>
  <div class="default-loader">
    <div class="loader-container">
      <img src="{!! asset('assets/images/loader.gif') !!}">
    </div>
  </div>
<body>
