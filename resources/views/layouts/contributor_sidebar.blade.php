<script>
  var user_coins;
  $( document ).ready(function() {
    user_coins="<?php echo $coins?>"
  });
</script>
<div class="menuCate">
  <div class="title">
    {!! t('Contributions') !!}
  </div>
  <ul class="menuListing">
    @if(Auth::check())
      @if(Auth::user()->hasRole(Config::get('constant.contributorRole.define')))
        <li class="{{ (Request::segment(2) == 'define') ? 'active' : '' }}"><a href="{!! lang_route('define') !!}" style="{{ (Request::segment(2) == 'define') ? 'background-position: 0 -40px' : '' }}"><span>{!! t('Define') !!}</span></a></li>
      @endif
      @if(Auth::user()->hasRole(Config::get('constant.contributorRole.illustrate')))
        <li class="{{ (Request::segment(2) == 'illustrate') ? 'active' : '' }}"><a href="{!! lang_route('illustrate') !!}" class="menu2" style="{{ (Request::segment(2) == 'illustrate') ? 'background-position: 0 -40px' : '' }}"><span>{!! t('Illustrate') !!}</span></a></li>
      @endif
      @if(Auth::user()->hasRole(Config::get('constant.contributorRole.translate')))
        <li class="{{ (Request::segment(2) == 'translate') ? 'active' : '' }}"><a href="{!! lang_route('translate') !!}" class="menu3" style="{{ (Request::segment(2) == 'translate') ? 'background-position: 0 -40px' : '' }}"><span>{!! t('Translate') !!}</span></a></li>
      @endif
    @endif
  </ul>
</div>
<div class="menuCate">
  <div class="title">
    {!! t('Earn Bonus Coins') !!}
  </div>
  <ul class="menuListing">
    @if(Auth::user()->hasRole(Config::get('constant.contributorRole.define')))
      <li class="{{ (Request::segment(2) == 'phrase-list') ? 'active' : '' }}"><a href="{!! lang_route('plist') !!}" class="menu1" style="{{ (Request::segment(2) == 'phrase-list') ? 'background-position: 0 -40px' : '' }}"><span>{!! t('Vote Meanings') !!}</span></a></li>
    @endif
    @if(Auth::user()->hasRole(Config::get('constant.contributorRole.illustrate')))
      <li class="{{ (Request::segment(2) == 'illustrator-vote-list') ? 'active' : '' }}"><a href="{!! lang_route('vIllustratorList') !!}" class="menu2" style="{{ (Request::segment(2) == 'illustrator-vote-list') ? 'background-position: 0 -40px' : '' }}"><span>{!! t('Vote Illustrations') !!}</span></a></li>
    @endif
    @if(Auth::user()->hasRole(Config::get('constant.contributorRole.translate')))
      <li class="{{ (Request::segment(2) == 'translate-vote-list') ? 'active' : '' }}"><a href="{!! lang_route('vTranslatorList') !!}" class="menu3" style="{{ (Request::segment(2) == 'translate-vote-list') ? 'background-position: 0 -40px' : '' }}"><span>{!! t('Vote Translations')!!}</span></a></li>
    @endif
  </ul>
</div>

<div class="menuCate">
  <div class="title">
    {!! t('Transactions') !!}
  </div>
  <ul class="menuListing">
    <li class="{{ (Request::segment(2) == 'coins-list') ? 'active' : '' }}"><a href="{!! lang_url('coins-list') !!}" class="menu5" style="{{ (Request::segment(2) == 'coins-list') ? 'background-position: 0 -40px' : '' }}"><span>{!! t('Purchase Coins') !!}</span></a></li>
    <li class="{{ (Request::segment(2) == 'redeem-points') ? 'active' : '' }}"><a href="{!! lang_url('redeem-points') !!}" class="menu6" style="{{ (Request::segment(2) == 'redeem-points') ? 'background-position: 0 -40px' : '' }}"><span>{!! t('Redeem points') !!}</span></a></li>
    <li class="{{ (Request::segment(2) == 'summary') ? 'active' : '' }}"><a href="{!! lang_url('summary') !!}" class="menu7" style="{{ (Request::segment(2) == 'summary') ? 'background-position: 0 -40px' : '' }}"><span>{!! t('Summary') !!}</span></a></li>
    <li class="{{ (Request::segment(2) == 'user-history') ? 'active' : '' }}"><a href="{!! lang_url('user-history') !!}" class="menu12" style="{{ (Request::segment(2) == 'user-history') ? 'background-position: 0 -40px' : '' }}"><span>{!! t('History') !!}</span></a></li>
  </ul>
</div>

