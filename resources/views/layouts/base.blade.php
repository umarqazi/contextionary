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
  {!! HTML::style('https://use.fontawesome.com/releases/v5.1.1/css/all.css') !!}
  @yield('head_css')
  {{-- End head css --}}
  {!! HTML::script('assets/js/jquery-3.3.1.min.js') !!}
</head>
<body>
  <div class="topHeader">
    <div class="row">
      <div class="col-sm-6">
        <div class="phoneNumber">
          <i class="fa fa-mobile-alt"></i> <a href="#">+1 734-747-4294</a>
        </div>
      </div>
      <div class="col-sm-6 text-right">
        <div class="languageBar">
          <span class="active"><img src="{!! asset('assets/images/english-flag.png') !!}"> English <i class="fa fa-chevron-down"></i></span>
          <ul class="list">
            <a href="{!! lang_route('locale', ['locale'=>'en']) !!}"><li><img src="{!! asset('assets/images/english-flag.png') !!}"> English </li></a>
            <a href="{!! lang_route('locale', ['locale'=>'fr']) !!}"><li><img src="{!! asset('assets/images/french-flag.png') !!}"> Frech</li></a>
            <a href="{!! lang_route('locale', ['locale'=>'sp']) !!}"><li><img src="{!! asset('assets/images/spain-flag.png') !!}"> Spanish</li></a>
            <a href="{!! lang_route('locale', ['locale'=>'hi']) !!}"><li><img src="{!! asset('assets/images/hindi-flag.png') !!}"> Hindi</li></a>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <header>
    @include('layouts.header')
  </header>
  <section class="mainPageContainer">
    @yield('content')
    <footer>
      @include('layouts.footer')
    </footer>
  </section>
  {!! HTML::script('assets/js/popper.min.js') !!}
  {!! HTML::script('assets/js/bootstrap.min.js') !!}
  {!! HTML::script('assets/js/mdb.min.js') !!}
  {!! HTML::script('assets/js/custom.js') !!}
</body>
</html>
