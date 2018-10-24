@extends('layouts.base')
@section('title')
  {!! t('Contextionary') !!}
@stop
@section('content')
  <!--Login section-->
  <section class="landingPage">
    <img src="{!! asset('assets/images/landing-logo.png')!!}" class="logo">
    <div class="content">
      <h1 class="companyName">{!! t('Contextionary') !!}</h1>
      <p class="tagLine mb-3">{!! t('Your illustrated reading comprehension assistant') !!}</p>
      <div class="companyMission">
        <div class="title">
          {!! t('Vision') !!}
        </div>
        <div class="text">
          {!! t('Unite the world<br> around a universal<br> tongue') !!}
        </div>
      </div>
      <div class="companyMission">
        <div class="title">
          {!! t('Mission') !!}
        </div>
        <div class="text">
          {!! t('Accelerate vocabulary learning through lexical sets.<br> Share specialized knowledge across languages and cultures.<br> Make the world jargon intelligibleUnite the world around a universal tongue.') !!}
        </div>
      </div>
      <div class="companyMission">
        <div class="title">
          {!! t('Vision') !!}
        </div>
        <div class="text">
          {!! t('Unite the world<br> around a universal<br> tongue.') !!}
        </div>
      </div>

    </div>
    <div class="exploreSection">
      <a href="{{lang_route('switchLanguage', ['lang'=>'en'])}}" class="orangeBtn waves-light">Explore</a>
      <ul class="language">
        <li @if(Config::get('app.locale')==Config::get('multilang.locales.ch.locale')) class="active" @endif><a href="{{lang_route('switchLanguage', ['lang'=>'ch'])}}">中文</a></li>
        <li @if(Config::get('app.locale')==Config::get('multilang.locales.sp.locale')) class="active" @endif><a href="{{lang_route('switchLanguage', ['lang'=>'sp'])}}">ESPAÑOL</a></li>
        <li @if(Config::get('app.locale')==Config::get('multilang.locales.en.locale')) class="active" @endif><a href="{{lang_route('switchLanguage', ['lang'=>'en'])}}">ENGLISH</a></li>
        <li @if(Config::get('app.locale')==Config::get('multilang.locales.hi.locale')) class="active" @endif><a href="{{lang_route('switchLanguage', ['lang'=>'hi'])}}">हिन्दी</a></li>
        <li @if(Config::get('app.locale')==Config::get('multilang.locales.fr.locale')) class="active" @endif><a href="{{lang_route('switchLanguage', ['lang'=>'fr'])}}">FRANÇAIS</a></li>
      </ul>
    </div>

  </section>
@stop
@section('footer_scripts')

@stop