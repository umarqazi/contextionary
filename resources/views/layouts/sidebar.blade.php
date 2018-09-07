<aside>
    <div class="logBg">
        <a href="@if(Auth::check()) {!! lang_url('dashboard') !!} @else {!! lang_url('home') !!} @endif"> <img src="{!! asset('assets/images/logo2.png') !!}"> </a>
    </div>
    @if(Auth::check())
        @if(Auth::user()->hasRole(['basic plan', 'premium plan', 'advance plan']))
            @include('layouts.user_sidebar')
        @else
            @include('layouts.contributor_sidebar')
        @endif
    @endif
    <div class="menuCate">
        <div class="title">
            {!! t('Miscellaneous')!!}

        </div>
        <ul class="menuListing">
            <li><a href="{!! lang_route('fun-facts') !!}" class="menu12"><span>Fun Facts</span></a></li>
            <li><a href="{!! lang_route('contactUs') !!}" class="menu13"><span>Contact Us</span></a></li>
        </ul>
    </div>
</aside>
