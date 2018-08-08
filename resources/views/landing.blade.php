@include('layouts.base_header')
<!--Login section-->
<section class="landingPage">
  <img src="{!! asset('assets/images/landing-logo.png')!!}" class="logo">
  <div class="content">
    <h1 class="companyName">Contextionary</h1>
    <p class="tagLine mb-3">Your illustrated reading comprehension assistant</p>
    <div class="companyMission">
      <div class="title">
        Vision
      </div>
      <div class="text">
        Unite the world<br> around a universal<br> tongue.
      </div>
    </div>
    <div class="companyMission">
      <div class="title">
        Mission
      </div>
      <div class="text">
        Accelerate vocabulary learning through lexical sets.<br> Share specialized knowledge across languages and cultures.<br> Make the world jargon intelligibleUnite the world around a universal tongue.<br>
      </div>
    </div>
    <div class="companyMission">
      <div class="title">
        Vision
      </div>
      <div class="text">
        Unite the world<br> around a universal<br> tongue.
      </div>
    </div>

  </div>

  <div class="exploreSection">
    <a href="{{lang_route('switchLanguage', ['lang'=>'en'])}}" class="orangeBtn waves-light">Explore</a>
    <ul class="language">
      <li><a href="{{lang_route('switchLanguage', ['lang'=>'ch'])}}">中文</a></li>
      <li><a href="{{lang_route('switchLanguage', ['lang'=>'sp'])}}">ESPAÑOL</a></li>
      <li><a href="{{lang_route('switchLanguage', ['lang'=>'en'])}}">ENGLISH</a></li>
      <li><a href="{{lang_route('switchLanguage', ['lang'=>'hi'])}}">हिन्दी</a></li>
      <li><a href="{{lang_route('switchLanguage', ['lang'=>'fr'])}}">FRANÇAIS</a></li>
    </ul>
  </div>

</section>
@include('layouts.base_footer')
