<aside>
    <div class="logBg">
        <a href="@if(Auth::check()) {!! lang_url('dashboard') !!} @else {!! lang_url('home') !!} @endif"> <img src="{!! asset('assets/images/logo2.png') !!}"> </a>
    </div>
    <div class="sidebar-container">
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
                <li class="{{ (Request::segment(2) == 'fun-facts') ? 'active' : '' }}"><a href="{!! lang_route('fun-facts') !!}" class="menu18" style="{{ (Request::segment(2) == 'fun-facts') ? 'background-position: 0 -40px' : '' }}"><span>{!! t('Fun Facts') !!}</span></a></li>
                @if(Auth::check())
                    @if(Auth::user()->hasRole(Config::get('constant.userRole.premium plan')))
                        <li class="{{ (Request::segment(2) == 'l-center') ? 'active' : '' }}" ><a href="{!! lang_route('l-center') !!}" class="menu11" style="{{ (Request::segment(2) == 'l-center') ? 'background-position: 0 -40px' : '' }}"><span>{!! t('Learning Center') !!}</span></a></li>
                    @endif
                @endif
                <li class="{{ (Request::segment(2) == 'contact-us') ? 'active' : '' }}"><a href="{!! lang_route('contactUs') !!}" class="menu13" style="{{ (Request::segment(2) == 'contact-us') ? 'background-position: 0 -40px' : '' }}"><span>{!! t('Contact Us') !!}</span></a></li>
            </ul>
        </div>
    </div>
</aside>
