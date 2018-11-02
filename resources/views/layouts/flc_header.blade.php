<div class="row">
    <div class="col-md-12">
        <div class="tabsContainer">
            <ul class="customTabs tabsView">
                @if($pageMenu)
                    @foreach($pageMenu as $key=>$menu)
                        <li class="{{ (Request::segment(2) == $key) ? 'active' : '' }}"><a href="{!! lang_url($key) !!}">{!! $menu !!}</a></li>
                    @endforeach
                @endif
            </ul>
        </div>
    </div>
</div>