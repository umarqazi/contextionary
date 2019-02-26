<div class="userHeader">
  <div class="row">
    <div class="col-md-7 col-sm-4">
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
          <div class="switch-account dropDown mr-0">
            <span class="name cursor">
              <a href="{!! lang_route('notification') !!}">
                <i class="fa fa-bell grey-org-clr"></i>
                <sup class="red-text">
                  <strong>
                    @if($notification > 0){!! $notification !!}@endif
                  </strong>
                </sup>
              </a>
            </span>
          </div>
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
              {{--@if(Auth::user()->hasRole(Config::get('constant.userRole')))--}}
              {{--<li>--}}
              {{--<a href="{!! lang_route('activeUserPlan') !!}">--}}
              {{--<div class="img-holder"><img src="{!! asset('assets/images/user-plan-icon.png') !!}"></div> {!! t('User Plan') !!}--}}
              {{--</a>--}}
              {{--</li>--}}
              {{--@endif--}}
              <li>
                <a href="{!! lang_route('profile') !!}">
                  <div class="img-holder"><img src="{!! asset('assets/images/view-profile-icon.png') !!}"></div> {!! t('View Profile') !!}
                </a>
              </li>
              @if(!Auth::user()->hasRole(Config::get('constant.userRole')))
                <li>
                  <a href="{!! lang_route('tutorials-con') !!}">
                    <div class="img-holder"><img src="{!! asset('assets/images/tutorial-icon.png') !!}"></div> {!! t('Tutorial') !!}
                  </a>
                </li>
              @endif
              <li>
                <a href="{!! lang_route('logout') !!}">
                  <div class="img-holder"><img src="{!! asset('assets/images/logout-icon.png') !!}"></div> {!! t('Logout') !!}
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
    <div class="col-md-5 col-sm-8 text-right">
      <div class="switch-account dropDown">
        @if(Auth::check())
          @if(Auth::user()->hasRole(Config::get('constant.contributorRole')))
            <img src="{!! asset('assets/images/switch-account-icon.png') !!}"> <span> <i class="fa fa-angle-down"></i></span>
            <div class="dropDown-block right-dropdown">
              <a href="{!! lang_url('switchToUser') !!}" class="account"><i class="fa fa-angle-right"></i> {!! t('Switch to User Account') !!}</a>
            </div>
          @else
            @if(!Auth::user()->hasRole(Config::get('constant.userRole.guest')))
              <img src="{!! asset('assets/images/switch-account-icon.png') !!}"> <span> <i class="fa fa-angle-down"></i></span>
              <div class="dropDown-block right-dropdown">
                <a href="{!! lang_url('switchToContributor') !!}" class="account"><i class="fa fa-angle-right"></i> {!! t('Switch to Contributor Account') !!}</a>
              </div>
            @endif
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
                  <td>{!! $contributions['points'][env('MEANING', 'meaning')]!!}</td>
                  <td>{!! $contributions['points'][env('ILLUSTRATE', 'illustrate')] !!}</td>
                  <td>{!! $contributions['points'][env('TRANSLATE', 'translate')] !!}</td>
                </tr>
                <tr>
                  <td class="name">{!! t('My Value') !!}</td>
                  <td>${!! $contributions['earning'][env('MEANING', 'meaning')] !!}</td>
                  <td>${!! $contributions['earning'][env('ILLUSTRATE', 'illustrate')] !!}</td>
                  <td>${!! $contributions['earning'][env('TRANSLATE', 'translate')] !!}</td>
                </tr>
                <tr>
                  <td class="name">{!! t('My Contributions') !!}</td>
                  <td>{!! $contributions['user_contributions'][env('MEANING', 'meaning')] !!}</td>
                  <td>{!! $contributions['user_contributions'][env('ILLUSTRATE', 'illustrate')] !!}</td>
                  <td>{!! $contributions['user_contributions'][env('TRANSLATE', 'translate')] !!}</td>
                </tr>
                <tr>
                  <td class="name">{!! t('My Pole Positions') !!}</td>
                  <td>{!! $contributions['user_pole_positions'][env('MEANING', 'meaning')] !!}</td>
                  <td>{!! $contributions['user_pole_positions'][env('ILLUSTRATE', 'illustrate')] !!}</td>
                  <td>{!! $contributions['user_pole_positions'][env('TRANSLATE', 'translate')] !!}</td>
                </tr>
                <tr>
                  <td class="name">{!! t('My Runner-ups') !!}</td>
                  <td>{!! $contributions['user_runner_up'][env('MEANING', 'meaning')] !!}</td>
                  <td>{!! $contributions['user_runner_up'][env('ILLUSTRATE', 'illustrate')] !!}</td>
                  <td>{!! $contributions['user_runner_up'][env('TRANSLATE', 'translate')] !!}</td>
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
                  <td class="name">{!! t('Highest Points') !!}</td>
                  <td>{!! $contributions['otherContributors'][env('MEANING', 'meaning')] !!}</td>
                  <td>{!! $contributions['otherContributors'][env('ILLUSTRATE', 'illustrate')] !!}</td>
                  <td>{!! $contributions['otherContributors'][env('TRANSLATE', 'translate')] !!}</td>
                </tr>
                <tr>
                  <td class="name">{!! t('Highest Value') !!}</td>
                  <td>${!! $contributions['otherContributorsRedeem'][env('MEANING', 'meaning')] !!}</td>
                  <td>${!! $contributions['otherContributorsRedeem'][env('ILLUSTRATE', 'illustrate')] !!}</td>
                  <td>${!! $contributions['otherContributorsRedeem'][env('TRANSLATE', 'translate')] !!}</td>
                </tr>
              </table>
            </div>
          </div>
        @endif
      @endif
      {{--<div class="languageBar">--}}
      {{--<span class="active"><img src="{!! asset('assets/images/') !!}/{!!Config::get('multilang.locales.'.App::getLocale().'.flag') !!}"> {!! Config::get('multilang.locales.'.App::getLocale().'.name') !!} <i class="fa fa-chevron-down"></i></span>--}}
      {{--<ul class="list">--}}
      {{--<li><a href="{!! lang_route('locale', ['locale'=>'en']) !!}"><img src="{!! asset('assets/images/english-flag.png') !!}"> English </a></li>--}}
      {{--<li><a href="{!! lang_route('locale', ['locale'=>'fr']) !!}"><img src="{!! asset('assets/images/french-flag.png') !!}"> French </a></li>--}}
      {{--<li><a href="{!! lang_route('locale', ['locale'=>'sp']) !!}"><img src="{!! asset('assets/images/spain-flag.png') !!}"> Spanish </a></li>--}}
      {{--<li><a href="{!! lang_route('locale', ['locale'=>'hi']) !!}"><img src="{!! asset('assets/images/hindi-flag.png') !!}"> Hindi </a></li>--}}
      {{--<li><a href="{!! lang_route('locale', ['locale'=>'ch']) !!}"><img src="{!! asset('assets/images/china-flag.png') !!}"> Chinese </a></li>--}}
      {{--</ul>--}}
      {{--</div>--}}

    </div>
  </div>
</div>
