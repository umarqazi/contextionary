@if (in_array(Route::current()->getName(), [Config::get('app.locale').'.funFacts',Config::get('app.locale').'.contactUs',]))
<div class="col-md-12">
    <div class="tabsContainer">
        <ul class="customTabs tabsView">
            <li class="{{ Request::path() == Config::get('app.locale').'/funFacts' ? 'active' : '' }}"><a href="{!! lang_route('fun-facts') !!}">Fun Facts</a></li>
            @if(Auth::check())
                <li class="{{ Request::path() == Config::get('app.locale').'/learning' ? 'active' : '' }}"><a href="#">Learning center</a></li>
            @endif
            <li class="{{ Request::path() == Config::get('app.locale').'/contactUs' ? 'active' : '' }}"><a href="{!! lang_route('contactUs') !!}">Contact us</a></li>
        </ul>
        @include('search')
    </div>
</div>
@endif
@if (in_array(Route::current()->getName(), [Config::get('app.locale').'.coins']))
<div class="row">
    <div class="col-md-12">
        <div class="tabsContainer">
            <ul class="customTabs tabsView">
                <li class="active"><a href="#">Purchase coins</a></li>
                <li><a href="#">redeem points</a></li>
                <li><a href="#">Summary</a></li>
            </ul>
            @include('search')
        </div>
    </div>
</div>
@endif