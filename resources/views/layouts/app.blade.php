@include('layouts.base_header')
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
@include('layouts.base_footer')
