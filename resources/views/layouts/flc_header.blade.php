<div class="row">
    <div class="col-md-12">
        <div class="tabsContainer">
            <ul class="customTabs tabsView">
                @if($pageMenu)
                    @foreach($pageMenu as $key=>$menu)
                        <li class="{{ Request::path() == Config::get('app.locale').'/'.$key ? 'active' : '' }}"><a href="{!! lang_url($key) !!}">{!! $menu !!}</a></li>
                    @endforeach
                @endif
            </ul>
            @include('search')
        </div>
    </div>
</div>