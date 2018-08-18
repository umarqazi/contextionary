<div class="col-md-12">
    <div class="tabsContainer">
        <ul class="customTabs tabsView">
            <li class="{{ Request::path() == Config::get('app.locale').'/funFacts' ? 'active' : '' }}"><a href="{!! lang_route('funFacts') !!}">Fun facts</a></li>
            @if(Auth::check())
                <li class="{{ Request::path() == Config::get('app.locale').'/learning' ? 'active' : '' }}"><a href="#">Learning center</a></li>
            @endif
            <li class="{{ Request::path() == Config::get('app.locale').'/contactUs' ? 'active' : '' }}"><a href="{!! lang_route('contactUs') !!}">Contact us</a></li>
        </ul>
        <div class="searchHolder light">
            <i class="fa fa-search"></i>
            <input type="search" class="fld" placeholder="Search">
        </div>
    </div>
</div>