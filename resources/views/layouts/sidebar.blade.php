<aside>
    <div class="logBg">
        <a href="{!! lang_route('home') !!}"> <img src="{!! asset('assets/images/logo2.png') !!}"> </a>
    </div>
    @if(Auth::user()->hasRole(['basic plan', 'premium plan', 'advance plan']))
      @include('layouts.user_sidebar')
    @else
      @include('layouts.contributor.sidebar')
    @endif
</aside>
