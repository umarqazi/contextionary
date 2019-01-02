@if(Auth::check())
    @if(!Auth::user()->hasRole('guest'))
        <div class="menuCate">
            <div class="title">
                {!! t('Reading Assistant')!!}
            </div>
            <ul class="menuListing">
                <li class="{{ (Request::segment(2) == 'context-finder') ? 'active' : '' }}"><a href="{!! lang_route('context-finder') !!}" class="menu4" style="{{ (Request::segment(2) == 'context-finder') ? 'background-position: 0 -40px' : '' }}"><span>Context Finder</span></a></li>
                <li class="{{ (Request::segment(2) == 'text-history') ? 'active' : '' }}">
                    <a href="{!! lang_route('text-history') !!}" class="menu4" style="{{ (Request::segment(2) == 'text-history') ? 'background-position: 0 -40px' : '' }}">
                        <span>Text History</span>
                    </a>
                </li>
            </ul>
        </div>
        {{--<div class="menuCate">--}}
            {{--<div class="title">--}}
                {{--{!! t('Glossary Catalog')!!}--}}
            {{--</div>--}}
            {{--<ul class="menuListing">--}}
                {{--<li class="{{ (Request::segment(2) == 'glossary') ? 'active' : '' }}">--}}
                    {{--<a href="{!! lang_route('glossary') !!}" class="menu5" style="{{ (Request::segment(2) == 'glossary') ? 'background-position: 0 -40px' : '' }}">--}}
                        {{--<span>Glossary Catalog</span>--}}
                    {{--</a>--}}
                {{--</li>--}}
                {{--<li class="{{ (Request::segment(2) == 'my-collection') ? 'active' : '' }}">--}}
                    {{--<a href="{!! lang_route('my-collection') !!}" class="menu5" style="{{ (Request::segment(2) == 'my-collection') ? 'background-position: 0 -40px' : '' }}">--}}
                        {{--<span>My Collection</span>--}}
                    {{--</a>--}}
                {{--</li>--}}
            {{--</ul>--}}
        {{--</div>--}}
    @endif
@endif
