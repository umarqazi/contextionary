@extends('layouts.base')
@section('title')
{!! t('Home') !!}
@stop
@section('content')
  <div class="container">
    <div class="row">
      <div class="col-md-12 wow zoomIn" data-wow-delay="0.4s">
        <div class="companyWrapper">
          <div class="companyName">
            Contextionary
          </div>
          <p>{!! t('Guest User Description')!!}</p>
          @if(!Auth::check())
            <div class="actions-btn">
              <a href="{!! lang_url('login') !!}" class="orangeBtn waves-light mr-3">{!! t('Log in') !!}</a>
              <a href="{!! lang_url('register') !!}" class="orangeBtn waves-light">{!! t('Sign up') !!}</a>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
  @stop
  @section('footer_scripts')

  @stop
