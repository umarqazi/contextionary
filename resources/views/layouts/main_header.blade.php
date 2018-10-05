<div class="userHeader">
  <div class="row">
    <div class="col-md-7 col-sm-6">
      <img src="{!! asset('assets/images/dashboard-icon.png') !!}" class="menuIcon cursor">
      <img src="{!! asset('assets/images/dashboard-icon.png') !!}" class="mobileIcon cursor">
      @if(Auth::check())
        <div class="userAvtar dropDown">
          <div class="img-holder">
            @if(Auth::user()->profile_image)
              <img src="{!! asset('storage/').'/'.Auth::user()->profile_image !!}">
            @else
              <img src="{!! asset('assets/images/default.jpg')!!}">
            @endif
          </div>
          <span class="name cursor"><i class="fa fa-chevron-down"></i></span>
          <div class="dropDown-block">
            <div class="avtar-holder">
              <div class="img-holder">
                @if(Auth::user()->profile_image)
                  <img src="{!! asset('storage/').'/'.Auth::user()->profile_image !!}">
                @else
                  <img src="{!! asset('assets/images/default.jpg')!!}">
                @endif
              </div>
              <span class="name">{!! Auth::user()->first_name !!} {!! Auth::user()->last_name !!}</span>
            </div>
            <ul class="userMenu">
              @if(Auth::user()->hasRole(Config::get('constant.userRole')))
                <li>
                  <a href="#">
                    <div class="img-holder"><img src="{!! asset('assets/images/user-plan-icon.png') !!}"></div> User Plan
                  </a>
                </li>
              @endif
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
              <p>{!! Config::get('constant.contributorNames.'.$role) !!} @if($role==Config::get('constant.contributorRole.translate')) ({!! Auth::user()->profile->language_proficiency !!}) @endif</p>
            @endforeach
          </div>
        @endif
      @endif
    </div>
    <div class="col-md-5 col-sm-6 text-right">
      <div class="switch-account dropDown">
        @if(Auth::user()->hasRole(Config::get('constant.contributorRole')))
          <img src="{!! asset('assets/images/switch-account-icon.png') !!}"> <span> <i class="fa fa-angle-down"></i></span>
          <div class="dropDown-block">
            <a href="{!! lang_url('switchToUser') !!}" class="account"><i class="fa fa-angle-right"></i> {!! t('Switch to User Account') !!}</a>
          </div>
        @else
          @if(Auth::user()->hasRole(Config::get('constant.userRole.premium plan')))
            <img src="{!! asset('assets/images/switch-account-icon.png') !!}"> <span> <i class="fa fa-angle-down"></i></span>
            <div class="dropDown-block">
              <a href="{!! lang_url('switchToContributor') !!}" class="account"><i class="fa fa-angle-right"></i> {!! t('Switch to Contibutor Account') !!}</a>
            </div>
          @endif
        @endif
      </div>
      @if(Auth::check())
        @if(Auth::user()->hasRole(Config::get('constant.contributorRole')))
          <div class="rightMenu">
            <a href="#" class="menu"><span>{!! t('Statistics') !!}</span></a>
            <div class="rightDropdown">
              <h2 class="statistics-heading">{!! t('my Statistics') !!}</h2>
              <h2 class="statistics-heading"> <p>{!! t('Coins') !!} ({!! ($coins!=NULL)? $coins:'0' !!})</p></h2>
              <table class="customTable">
                <tr>
                  <td class="name"></td>
                  <td><img src="{!! asset('assets/images/statistics-icon1.png') !!}"></td>
                  <td><img src="{!! asset('assets/images/statistics-icon2.png') !!}"></td>
                  <td><img src="{!! asset('assets/images/statistics-icon3.png') !!}"></td>
                </tr>
                <tr>
                  <td class="name">{!! t('My Points') !!}</td>
                  <td>{!! $points[env('MEANING')]!!}</td>
                  <td>{!! $points[env('ILLUSTRATE')] !!}</td>
                  <td>{!! $points[env('TRANSLATE')] !!}</td>
                </tr>
                <tr>
                  <td class="name">{!! t('My Earning') !!}</td>
                  <td>0</td>
                  <td>0</td>
                  <td>0</td>
                </tr>
                <tr>
                  <td class="name">{!! t('My Contributions') !!}</td>
                  <td>{!! $contributions[env('MEANING')] !!}</td>
                  <td>{!! $contributions[env('ILLUSTRATE')] !!}</td>
                  <td>{!! $contributions[env('TRANSLATE')] !!}</td>
                </tr>
                <tr>
                  <td class="name">{!! t('My Pole Positions') !!}</td>
                  <td>{!! $pole[env('MEANING')] !!}</td>
                  <td>{!! $pole[env('ILLUSTRATE')] !!}</td>
                  <td>{!! $pole[env('TRANSLATE')] !!}</td>
                </tr>
                <tr>
                  <td class="name">{!! t('My Runner-ups') !!}</td>
                  <td>{!! $runnerUp[env('MEANING')] !!}</td>
                  <td>{!! $runnerUp[env('ILLUSTRATE')] !!}</td>
                  <td>{!! $runnerUp[env('TRANSLATE')] !!}</td>
                </tr>
              </table>
              <h2 class="mt-3">{!! t('Other Contributors') !!}</h2>
              <table class="customTable">
                <tr>
                  <td class="name"></td>
                  <td><img src="{!! asset('assets/images/statistics-icon1.png') !!}"></td>
                  <td><img src="{!! asset('assets/images/statistics-icon2.png') !!}"></td>
                  <td><img src="{!! asset('assets/images/statistics-icon3.png') !!}"></td>
                </tr>
                <tr>
                  <td class="name">{!! t('Highest Coins') !!}</td>
                  <td>0</td>
                  <td>0</td>
                  <td>0</td>
                </tr>
                <tr>
                  <td class="name">{!! t('Highest Points') !!}</td>
                  <td>0</td>
                  <td>0</td>
                  <td>0</td>
                </tr>
                <tr>
                  <td class="name">{!! t('Highest Earning') !!}</td>
                  <td>0</td>
                  <td>0</td>
                  <td>0</td>
                </tr>
              </table>
            </div>
          </div>
        @endif
      @endif
      <div class="languageBar">
        <span class="active"><img src="{!! asset('assets/images/') !!}/{!!Config::get('multilang.locales.'.App::getLocale().'.flag') !!}"> {!! Config::get('multilang.locales.'.App::getLocale().'.name') !!} <i class="fa fa-chevron-down"></i></span>
        <ul class="list">
          <a href="{!! lang_route('locale', ['locale'=>'en']) !!}"><li><img src="{!! asset('assets/images/english-flag.png') !!}"> English </li></a>
          <a href="{!! lang_route('locale', ['locale'=>'fr']) !!}"><li><img src="{!! asset('assets/images/french-flag.png') !!}"> French</li></a>
          <a href="{!! lang_route('locale', ['locale'=>'sp']) !!}"><li><img src="{!! asset('assets/images/spain-flag.png') !!}"> Spanish</li></a>
          <a href="{!! lang_route('locale', ['locale'=>'hi']) !!}"><li><img src="{!! asset('assets/images/hindi-flag.png') !!}"> Hindi</li></a>
        </ul>
      </div>

    </div>
  </div>
</div>
