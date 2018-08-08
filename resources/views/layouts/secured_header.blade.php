<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <title>@yield('title')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="author" content="">
  {!! HTML::style('assets/css/bootstrap.min.css') !!}
  {!! HTML::style('assets/css/mdb.min.css') !!}
  {!! HTML::style('assets/css/main.css') !!}
  {!! HTML::style('assets/css/responsive.css') !!}
  {!! HTML::style('assets/css/style.css') !!}
  {!! HTML::style('assets/css/shards.css') !!}
  {!! HTML::style('https://use.fontawesome.com/releases/v5.1.1/css/all.css') !!}
  @yield('head_css')
  {{-- End head css --}}
  {!! HTML::script('assets/js/jquery-3.3.1.min.js') !!}
  <script type="text/javascript">
    var expireDate="<?php echo  date('M d Y h:i:s', Auth::user()->expiry_date); ?>";
    </script>
</head>
<body>
    @include('layouts.sidebar')
    <section class="dashBoard-container">
      @include('layouts.main_header')
      @yield('content')
    </section>

  {!! HTML::script('assets/js/popper.min.js') !!}
  {!! HTML::script('assets/js/bootstrap.min.js') !!}
  {!! HTML::script('assets/js/mdb.min.js') !!}
  {!! HTML::script('assets/js/shards.min.js') !!}
  {!! HTML::script('assets/js/custom.js') !!}
</body>
</html>
