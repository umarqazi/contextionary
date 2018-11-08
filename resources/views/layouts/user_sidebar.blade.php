@if(Auth::check())
    @if(!Auth::user()->hasRole('guest'))
        <div class="menuCate">
            <div class="title">
                {!! t('Reading Assistant')!!}
            </div>
            <ul class="menuListing">
                <li><a href="#" class="menu4"><span>Context Finder</span></a></li>
                <li><a href="#" class="menu4"><span>Text History</span></a></li>
                <li class="">
                    <a href="{!! lang_route('tutorials') !!}" class="menu4" style="{{ (Request::segment(2) == 'tutorials') ? 'background-position: 0 -40px' : '' }}">
                        <span>Tutorials</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="menuCate">
            <div class="title">
                {!! t('Glossary Catalog')!!}
            </div>
            <ul class="menuListing">
                <li class="{{ (Request::segment(2) == 'glossary') ? 'active' : '' }}">
                    <a href="{!! lang_route('glossary') !!}" class="menu5" style="{{ (Request::segment(2) == 'glossary') ? 'background-position: 0 -40px' : '' }}">
                        <span>Glossary Catalog</span>
                    </a>
                </li>
                <li class="{{ (Request::segment(2) == 'my-collection') ? 'active' : '' }}">
                    <a href="{!! lang_route('my-collection') !!}" class="menu5" style="{{ (Request::segment(2) == 'my-collection') ? 'background-position: 0 -40px' : '' }}">
                        <span>My Collection</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="menuCate">
            <div class="title">
                {!! t('A Game of Context')!!}
            </div>
            <ul class="menuListing">
                <li class="{{ (Request::segment(2) == 'pictionary') ? 'active' : '' }}">
                    <a href="{!! lang_route('start-pictionary') !!}" style="{{ (Request::segment(2) == 'pictionary') ? 'background-position: 0 -40px' : '' }}">
                        <span>Pictionary</span>
                    </a>
                </li>
                <li class="{{ (Request::segment(2) == 'intruder') ? 'active' : '' }}">
                    <a href="{!! lang_route('intruder') !!}" class="menu2" style="{{ (Request::segment(2) == 'intruder') ? 'background-position: 0 -40px' : '' }}">
                        <span>Spot the Intruder</span>
                    </a>
                </li>
                <li class="{{ (Request::segment(2) == 'hangman') ? 'active' : '' }}">
                    <a href="{!! lang_route('start-hangman') !!}" class="menu3" style="{{ (Request::segment(2) == 'hangman') ? 'background-position: 0 -40px' : '' }}">
                        <span>Hangman</span>
                    </a>
                </li>
            </ul>
        </div>
    @endif
@endif
