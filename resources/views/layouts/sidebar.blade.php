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
        @if(Auth::check())
            <div class="menuCate">
                <div class="title">
                    {!! t('A Game of Contexts')!!}
                </div>
                <ul class="menuListing">
                    <li class="{{ (Request::segment(2) == 'pictionary') ? 'active' : '' }}">
                        <a href="{!! lang_route('start-pictionary') !!}" style="{{ (Request::segment(2) == 'pictionary') ? 'background-position: 0 -40px' : '' }}">
                            <span>Pictionary</span>
                        </a>
                    </li>
                    <li class="{{ (Request::segment(2) == 'intruder') ? 'active' : '' }}">
                        <a href="{!! lang_route('start-spot-the-intruder') !!}" class="menu2" style="{{ (Request::segment(2) == 'intruder') ? 'background-position: 0 -40px' : '' }}">
                            <span>Spot the Intruder</span>
                        </a>
                    </li>
                    <li class="{{ (Request::segment(2) == 'hangman' || Request::segment(2) == 'start-hangman') ? 'active' : ''  }}">
                        <a href="{!! lang_route('start-hangman') !!}" class="menu3" style="{{ (Request::segment(2) == 'hangman') ? 'background-position: 0 -40px' : '' }}">
                            <span>Hangman</span>
                        </a>
                    </li>
                </ul>
            </div>
        @endif

        <div class="menuCate">
            <div class="title">
                {!! t('Miscellaneous')!!}
            </div>
            <ul class="menuListing">
                <li class="{{ (Request::segment(2) == 'fun-facts') ? 'active' : '' }}"><a href="{!! lang_route('fun-facts') !!}" class="menu18" style="{{ (Request::segment(2) == 'fun-facts') ? 'background-position: 0 -40px' : '' }}"><span>{!! t('Word Facts') !!}</span></a></li>
                @if(Auth::check())
                    @if(Auth::user()->hasRole(Config::get('constant.userRole.premium plan')))
                        <li class="{{ (Request::segment(2) == 'learning-center') ? 'active' : '' }}" ><a href="{!! lang_route('l-center') !!}" class="menu11" style="{{ (Request::segment(2) == 'learning-center') ? 'background-position: 0 -32px' : '' }}"><span>{!! t('Learning Center') !!}</span></a></li>
                    @endif
                @endif
                <li class="{{ (Request::segment(2) == 'contact-us') ? 'active' : '' }}"><a href="{!! lang_route('contactUs') !!}" class="menu13" style="{{ (Request::segment(2) == 'contact-us') ? 'background-position: 0 -40px' : '' }}"><span>{!! t('Contact Us') !!}</span></a></li>
                <li class="{{ (Request::segment(2) == 'about-us') ? 'active' : '' }}"><a href="{!! lang_route('aboutUs') !!}" class="menu13" style="{{ (Request::segment(2) == 'about-us') ? 'background-position: 0 -40px' : '' }}"><span>{!! t('About Us') !!}</span></a></li>
            </ul>
        </div>
    </div>
</aside>
