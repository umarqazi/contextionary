
<div class="menuCate">
  <div class="title">
    {!! t('A Game of Context') !!}
  </div>
  <ul class="menuListing">
    @if(Auth::check())
      @if(Auth::user()->hasRole(Config::get('constant.contributorRole.definition')))
        <li><a href="{!! lang_route('define') !!}"><span>{!! t('Define') !!}</span></a></li>
      @endif
      @if(Auth::user()->hasRole(Config::get('constant.contributorRole.illustrator')))
        <li><a href="#" class="menu2"><span>{!! t('Illustrate') !!}</span></a></li>
      @endif
      @if(Auth::user()->hasRole(Config::get('constant.contributorRole.translator')))
        <li><a href="#" class="menu3"><span>{!! t('Translate') !!}</span></a></li>
      @endif
    @endif
  </ul>
</div>
<div class="menuCate">
  <div class="title">
    {!! t('Earn Bonus Coins') !!}
  </div>
  <ul class="menuListing">
    <li><a href="{!! lang_route('plist') !!}" class="menu4"><span>{!! t('Vote Meanings') !!}</span></a></li>
    <li><a href="#" class="menu4"><span>{!! t('Vote Illustration') !!}</span></a></li>
    <li><a href="#" class="menu4"><span>{!! t('Vote Translations')!!}</span></a></li>
  </ul>
</div>

<div class="menuCate">
  <div class="title">
    {!! t('Transactions') !!}
  </div>
  <ul class="menuListing">
    <li><a href="{!! lang_url('purchaseCoins') !!}" class="menu5"><span>{!! t('Purchase Coins') !!}</span></a></li>
    <li><a href="#" class="menu6"><span>{!! t('Redeem points') !!}</span></a></li>
    <li><a href="#" class="menu7"><span>{!! t('Summary') !!}</span></a></li>
  </ul>
</div>
