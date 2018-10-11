<div class="row">
    <div class="col-md-12">
        <div class="tabsContainer">
            <ul class="customTabs tabsView">
                @if($pageMenu)
                    @foreach($pageMenu as $key=>$menu)
                        <li class="{{ (Request::segment(2) == $key) ? 'active' : '' }} title">{!! $menu !!} <a href="{!! lang_route($key) !!}"><i class="fas fa-pencil-alt"></i></a></li>
                    @endforeach
                @endif
            </ul>
            @include('search')
        </div>
    </div>
</div>