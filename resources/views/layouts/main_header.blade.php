<div class="userHeader">
  <div class="row">
    <div class="col-md-8 col-sm-6">
      <img src="{!! asset('assets/images/dashboard-icon.png') !!}" class="menuIcon cursor">
      <img src="{!! asset('assets/images/dashboard-icon.png') !!}" class="mobileIcon cursor">
      @if(Auth::check())
        <div class="userAvtar dropDown">
          <div class="img-holder">
            <img src="{!! asset('storage/').'/'.Auth::user()->profile_image !!}">
          </div>
          <span class="name cursor">{!! Auth::user()->first_name !!} <i class="fa fa-chevron-down"></i></span>
          <div class="dropDown-block">
            <div class="avtar-holder">
              <div class="img-holder">
                <img src="{!! asset('storage/').'/'.Auth::user()->profile_image !!}">
              </div>
              <span class="name">{!! Auth::user()->first_name !!} {!! Auth::user()->last_name !!}</span>
            </div>
            <ul class="userMenu">
              <li>
                <a href="#">
                  <div class="img-holder"><img src="{!! asset('assets/images/user-plan-icon.png') !!}"></div> User Plan
                </a>
              </li>
              <li>
                <a href="{!! lang_route('profile') !!}">
                  <div class="img-holder"><img src="{!! asset('assets/images/view-profile-icon.png') !!}"></div> View Profile
                </a>
              </li>
              <li>
                <a href="{!! lang_route('logout') !!}">
                  <div class="img-holder"><img src="{!! asset('assets/images/logout-icon.png') !!}"></div> Log out
                </a>
              </li>
            </ul>
          </div>
        </div>
        @if(Auth::user()->hasRole(Config::get('constant.contributorRole')))
          <div class="topLinks">
            <?php $roles = Auth::user()->roles->pluck('name');?>
            @foreach($roles as $role)
              <a href="#">{!! $role !!} @if($role==Config::get('constant.contributorRole.translator')) ({!! Auth::user()->profile->language_proficiency !!}) @endif</a>
            @endforeach
          </div>
        @endif
      @endif
    </div>
    <div class="col-md-4 col-sm-6 text-right">
      <div class="languageBar">
        <span class="active"><img src="{!! asset('assets/images/') !!}/{!!Config::get('multilang.locales.'.App::getLocale().'.flag') !!}"> {!! Config::get('multilang.locales.'.App::getLocale().'.name') !!} <i class="fa fa-chevron-down"></i></span>
        <ul class="list">
          <a href="{!! lang_route('locale', ['locale'=>'en']) !!}"><li><img src="{!! asset('assets/images/english-flag.png') !!}"> English </li></a>
          <a href="{!! lang_route('locale', ['locale'=>'fr']) !!}"><li><img src="{!! asset('assets/images/french-flag.png') !!}"> Frech</li></a>
          <a href="{!! lang_route('locale', ['locale'=>'sp']) !!}"><li><img src="{!! asset('assets/images/spain-flag.png') !!}"> Spanish</li></a>
          <a href="{!! lang_route('locale', ['locale'=>'hi']) !!}"><li><img src="{!! asset('assets/images/hindi-flag.png') !!}"> Hindi</li></a>
        </ul>
      </div>
      @if(Auth::check())
        @if(Auth::user()->hasRole(Config::get('constant.contributorRole')))
          <div class="rightMenu">
            <a href="#" class="menu"></a>
            <div class="rightDropdown">
              <h2>my Statistics</h2>
              <table class="customTable">
                <tr>
                  <td class="name"></td>
                  <td><img src="{!! asset('assets/images/statistics-icon1.png') !!}"></td>
                  <td><img src="{!! asset('assets/images/statistics-icon2.png') !!}"></td>
                  <td><img src="{!! asset('assets/images/statistics-icon3.png') !!}"></td>
                </tr>
                <tr>
                  <td class="name">{!! t('My Coins') !!}</td>
                  <td>{!! $coins !!}</td>
                  <td>2</td>
                  <td>1</td>
                </tr>
                <tr>
                  <td class="name">{!! t('My Points') !!}</td>
                  <td>5</td>
                  <td>2</td>
                  <td>1</td>
                </tr>
                <tr>
                  <td class="name">{!! t('My Earning') !!}</td>
                  <td>5</td>
                  <td>2</td>
                  <td>1</td>
                </tr>
                <tr>
                  <td class="name">{!! t('My Contributions') !!}</td>
                  <td>{!! $Contributions !!}</td>
                  <td>2</td>
                  <td>1</td>
                </tr>
                <tr>
                  <td class="name">My Pole Positions</td>
                  <td>5</td>
                  <td>2</td>
                  <td>1</td>
                </tr>
                <tr>
                  <td class="name">My Runner-ups</td>
                  <td>5</td>
                  <td>2</td>
                  <td>1</td>
                </tr>
              </table>
              <h2 class="mt-3">Other contributors</h2>
              <table class="customTable">
                <tr>
                  <td class="name"></td>
                  <td><img src="{!! asset('assets/images/statistics-icon1.png') !!}"></td>
                  <td><img src="{!! asset('assets/images/statistics-icon2.png') !!}"></td>
                  <td><img src="{!! asset('assets/images/statistics-icon3.png') !!}"></td>
                </tr>
                <tr>
                  <td class="name">Highest Coins</td>
                  <td>5</td>
                  <td>2</td>
                  <td>1</td>
                </tr>
                <tr>
                  <td class="name">Highest Points</td>
                  <td>5</td>
                  <td>2</td>
                  <td>1</td>
                </tr>
                <tr>
                  <td class="name">Highest Earning</td>
                  <td>5</td>
                  <td>2</td>
                  <td>1</td>
                </tr>
              </table>
            </div>
          </div>
        @endif
      @endif
    </div>
  </div>
</div>
