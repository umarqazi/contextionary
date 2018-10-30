@if(Auth::check())
    @if(Auth::user()->user_roles != Config::get('constant.userRoleIds.guest'))
        <div class="menuCate">
            <div class="title">
                {!! t('Reading Assistant')!!}
            </div>
            <ul class="menuListing">
                <li><a href="#" class="menu4"><span>Context Finder</span></a></li>
                <li><a href="#" class="menu4"><span>Text History</span></a></li>
                <li><a href="{!! lang_route('tutorials') !!}" class="menu4"><span>Tutorials</span></a></li>
            </ul>
        </div>
        <div class="menuCate">
            <div class="title">
                {!! t('Glossary Catalog')!!}

            </div>
            <ul class="menuListing">
                <li><a href="{!! lang_route('glossary') !!}" class="menu5"><span>Glossary Catalog</span></a></li>
                <li><a href="{!! lang_route('my-collection') !!}" class="menu5"><span>My Collection</span></a></li>
            </ul>
        </div>
        <div class="menuCate">
            <div class="title">
                {!! t('A Game of Context')!!}

            </div>
            <ul class="menuListing">
                <li><a href="{!! lang_route('start-pictionary') !!}"><span>Pictionary</span></a></li>
                <li><a href="{!! lang_route('start-spot-the-intruder') !!}" class="menu2"><span>Spot the Intruder</span></a></li>
                <li><a href="#" class="menu3"><span>Hangman</span></a></li>
            </ul>
        </div>
    @endif
@endif