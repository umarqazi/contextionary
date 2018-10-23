<div class="row">
    <div class="col-md-12">
        <div class="tabsContainer">
            <ul class="customTabs tabsView">
                <li class="{{ (Request::segment(2) == 'profile') ? 'active' : '' }} title profile-anchor"><a href="{!! lang_route('profile') !!}"> {!! t('My Profile') !!}</a></li>
                <li class="{{ (Request::segment(2) == 'edit-roles') ? 'active' : '' }} title profile-anchor"><a href="{!! lang_url('edit-roles') !!}">{!! t('Roles & Context') !!}</a></li>
            </ul>
            @include('search')
        </div>
    </div>
</div>