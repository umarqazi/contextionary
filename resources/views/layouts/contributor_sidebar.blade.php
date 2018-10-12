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
        <li><a href="{!! lang_route('define') !!}"><span>{!! t('Define') !!}</span></a></li>
      @endif
      @if(Auth::user()->hasRole(Config::get('constant.contributorRole.illustrate')))
        <li><a href="{!! lang_route('illustrate') !!}" class="menu2"><span>{!! t('Illustrate') !!}</span></a></li>
      @endif
      @if(Auth::user()->hasRole(Config::get('constant.contributorRole.translate')))
        <li><a href="{!! lang_route('translate') !!}" class="menu3"><span>{!! t('Translate') !!}</span></a></li>
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
      <li><a href="{!! lang_route('plist') !!}" class="menu4"><span>{!! t('Vote Meanings') !!}</span></a></li>
    @endif
    @if(Auth::user()->hasRole(Config::get('constant.contributorRole.illustrate')))
      <li><a href="{!! lang_route('vIllustratorList') !!}" class="menu4"><span>{!! t('Vote Illustration') !!}</span></a></li>
    @endif
    @if(Auth::user()->hasRole(Config::get('constant.contributorRole.translate')))
      <li><a href="{!! lang_route('vTranslatorList') !!}" class="menu4"><span>{!! t('Vote Translations')!!}</span></a></li>
    @endif
  </ul>
</div>

<div class="menuCate">
  <div class="title">
    {!! t('Transactions') !!}
  </div>
  <ul class="menuListing">
    <li><a href="{!! lang_url('coins-list') !!}" class="menu5"><span>{!! t('Purchase Coins') !!}</span></a></li>
    <li><a href="{!! lang_url('redeem-points') !!}" class="menu6"><span>{!! t('Redeem points') !!}</span></a></li>
    <li><a href="{!! lang_url('summary') !!}" class="menu7"><span>{!! t('Summary') !!}</span></a></li>
    <li><a href="{!! lang_url('user-history') !!}" class="menu7"><span>{!! t('History') !!}</span></a></li>
  </ul>
</div>
