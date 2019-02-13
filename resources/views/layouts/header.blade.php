<div class="container-fluid">
  <div class="row">
    <div class="col-md-2">
      <a href="@if(Auth::check()) {!! lang_url('dashboard') !!} @else {!! lang_url('home') !!} @endif"><img src="{!! asset('assets/images/logo2.png') !!}" class="logo wow bounceInLeft" data-wow-delay="0.2s"></a>
    </div>
    <div class="col-md-10 text-right">
      <button id="menu"><i class="fa fa-bars"></i></button>
      <ul class="mainMenu">
        <i class="fa fa-times closeMenu"></i>
        <li><a href="{!! lang_url('about-us') !!}">{!! t('About Us') !!}</a></li>
        <li><a href="{!! lang_route('fun-facts') !!}">{!! t('Word Facts') !!}</a></li>
        <li><a href="{!! lang_route('start-hangman') !!}">{!! t('Hangman') !!}</a></li>
        <li><a href="{!! lang_route('contactUs') !!}">{!! t('Contact Us') !!}</a></li>
      </ul>
      {{--<div class="searchWrapper">--}}
        {{--<form>--}}
          {{--<div class="search">--}}
            {{--<i class="fa fa-search"></i>--}}
            {{--<input type="search" class="fld" placeholder="{!! t('Search')!!}">--}}
          {{--</div>--}}
        {{--</form>--}}
      {{--</div>--}}

    </div>
  </div>
</div>
