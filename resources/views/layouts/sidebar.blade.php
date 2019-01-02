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
                    <li>
                        <a href="{!! lang_route('start-pictionary') !!}" class="{{ (Request::segment(2) == 'pictionary') ? 'active' : '' }} menu20">
                            <span>Pictionary</span>
                        </a>
                    </li>
                    <li>
                        <a href="{!! lang_route('start-spot-the-intruder') !!}" class="{{ (Request::segment(2) == 'intruder') ? 'active' : '' }} menu20">
                            <span>Spot the Intruder</span>
                        </a>
                    </li>
                    <li>
                        <a href="{!! lang_route('start-hangman') !!}" class="{{ (Request::segment(2) == 'hangman' || Request::segment(2) == 'start-hangman') ? 'active' : ''  }} menu20">
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
                <li><a href="{!! lang_route('fun-facts') !!}" class="{{ (Request::segment(2) == 'fun-facts') ? 'active' : '' }} menu18"><span>{!! t('Word Facts') !!}</span></a></li>
                @if(Auth::check())
                    @if(Auth::user()->hasRole(Config::get('constant.userRole.premium plan')))
                        <li><a href="{!! lang_route('l-center') !!}" class="{{ (Request::segment(2) == 'learning-center') ? 'active' : '' }} menu11"><span>{!! t('Learning Center') !!}</span></a></li>
                    @endif
                @endif
                <li><a href="{!! lang_route('contactUs') !!}" class="{{ (Request::segment(2) == 'contact-us') ? 'active' : '' }} menu13"><span>{!! t('Contact Us') !!}</span></a></li>
                <li><a href="{!! lang_route('aboutUs') !!}" class="{{ (Request::segment(2) == 'about-us') ? 'active' : '' }} menu21"><span>{!! t('About Us') !!}</span></a></li>
            </ul>
        </div>
    </div>
</aside>
