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
</head>
<body>
  <section class="loginContainer">
    <div class="loginWrapper">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-12 text-center">
            <a href="{!! lang_url('/') !!}"><img src="{!! asset('assets/images/logo.png')!!}" class="logo wow bounceIn" data-wow-delay="0.2s"></a>
            <div class="loginIcon wow fadeIn" data-wow-delay="0.4s">
              <img src="{!! asset('assets/images/login-icon.png')!!}">
            </div>
            @yield('content')
          </div>
        </div>
      </div>
    </div>
  </section>
  {!! HTML::script('assets/js/popper.min.js') !!}
  {!! HTML::script('assets/js/bootstrap.min.js') !!}
  {!! HTML::script('assets/js/mdb.min.js') !!}
  {!! HTML::script('assets/js/shards.min.js') !!}
  {!! HTML::script('assets/js/custom.js') !!}
</body>
</html>
