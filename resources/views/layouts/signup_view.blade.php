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
    {!! HTML::style('assets/css/shards.css') !!}
  {!! HTML::style('assets/css/main.css') !!}
  {!! HTML::style('assets/css/responsive.css') !!}
  {!! HTML::style('assets/css/style.css') !!}
  {!! HTML::style('https://use.fontawesome.com/releases/v5.1.1/css/all.css') !!}
  <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css">
  @yield('head_css')
  {{-- End head css --}}
  {!! HTML::script('assets/js/jquery-3.3.1.min.js') !!}
</head>
<body>
  <section class="loginContainer">
      <div class="loginWrapper">
          <div class="container">
              <div class="row justify-content-center signUpRow">
                  <div class="col-md-12 text-center">
                      <a href="{!! lang_route('home') !!}"><img src="{!! asset('assets/images/logo.png') !!}" class="logo wow bounceIn" data-wow-delay="0.2s"></a>
                  </div>
            @yield('content')
          </div>
      </div>
  </div>
  </section>
  {!! HTML::script('assets/js/toaster.js') !!}
  {!! HTML::script('assets/js/popper.min.js') !!}
  {!! HTML::script('assets/js/bootstrap.min.js') !!}
  {!! HTML::script('assets/js/mdb.min.js') !!}
  {!! HTML::script('assets/js/shards.min.js') !!}
  {!! HTML::script('assets/js/custom.js') !!}
</body>
</html>
