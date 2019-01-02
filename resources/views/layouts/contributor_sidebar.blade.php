<script>
  var user_coins;
  $( document ).ready(function() {
    user_coins="<?php echo $coins?>"
  });
</script>
<div class="menuCate">
  <div class="title">
    Contribute
  </div>
  <ul class="menuListing">
    @if(Auth::check())
      @if(Auth::user()->hasRole(Config::get('constant.contributorRole.define')))
        <li><a href="{!! lang_route('define') !!}" class="{{ (Request::segment(2) == 'define') ? 'active' : '' }} menu1"><span>{!! t('Define') !!}</span></a></li>
      @endif
      @if(Auth::user()->hasRole(Config::get('constant.contributorRole.illustrate')))
        <li><a href="{!! lang_route('illustrate') !!}" class="{{ (Request::segment(2) == 'illustrate') ? 'active' : '' }} menu2"><span>{!! t('Illustrate') !!}</span></a></li>
      @endif
      @if(Auth::user()->hasRole(Config::get('constant.contributorRole.translate')))
        <li><a href="{!! lang_route('translate') !!}" class="{{ (Request::segment(2) == 'translate') ? 'active' : '' }} menu3"><span>{!! t('Translate') !!}</span></a></li>
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
      <li><a href="{!! lang_route('plist') !!}" class="{{ (Request::segment(2) == 'phrase-list') ? 'active' : '' }} menu1"><span>{!! t('Vote Meaning') !!}</span></a></li>
    @endif
    @if(Auth::user()->hasRole(Config::get('constant.contributorRole.illustrate')))
      <li><a href="{!! lang_route('vIllustratorList') !!}" class="{{ (Request::segment(2) == 'illustrator-vote-list') ? 'active' : '' }} menu2"><span>{!! t('Vote Illustration') !!}</span></a></li>
    @endif
    @if(Auth::user()->hasRole(Config::get('constant.contributorRole.translate')))
      <li><a href="{!! lang_route('vTranslatorList') !!}" class="{{ (Request::segment(2) == 'translate-vote-list') ? 'active' : '' }} menu3"><span>{!! t('Vote Translation')!!}</span></a></li>
    @endif
  </ul>
</div>

<div class="menuCate">
  <div class="title">
    {!! t('Transact') !!}
  </div>
  <ul class="menuListing">
    <li><a href="{!! lang_url('coins-list') !!}" class="{{ (Request::segment(2) == 'coins-list') ? 'active' : '' }} menu5"><span>{!! t('Purchase Coins') !!}</span></a></li>
    <li><a href="{!! lang_url('redeem-points') !!}" class="{{ (Request::segment(2) == 'redeem-points') ? 'active' : '' }} menu6"><span>{!! t('Redeem points') !!}</span></a></li>
    <li><a href="{!! lang_url('summary') !!}" class="{{ (Request::segment(2) == 'summary') ? 'active' : '' }} menu7"><span>{!! t('Summary') !!}</span></a></li>
    <li><a href="{!! lang_url('user-history') !!}" class="{{ (Request::segment(2) == 'user-history') ? 'active' : '' }} menu12"><span>{!! t('History') !!}</span></a></li>
  </ul>
</div>

