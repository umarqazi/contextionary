<aside>
    <div class="logBg">
        <a href="@if(Auth::check()) {!! lang_url('dashboard') !!} @else {!! lang_url('home') !!} @endif"> <img src="{!! asset('assets/images/logo2.png') !!}"> </a>
    </div>
    @if(Auth::check())
        @if(Auth::user()->hasRole(Config::get('constant.userRole')))
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
            <li><a href="{!! lang_route('fun-facts') !!}" class="menu12"><span>{!! t('Fun Facts') !!}</span></a></li>
            @if(Auth::check())
                @if(Auth::user()->hasRole(Config::get('constant.userRole.premium plan')))
                    <li><a href="" class="menu11"><span>{!! t('Learning Center') !!}</span></a></li>
                @endif
            @endif
            <li><a href="{!! lang_route('contactUs') !!}" class="menu13"><span>{!! t('Contact Us') !!}</span></a></li>
        </ul>
    </div>
</aside>
