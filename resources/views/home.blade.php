@extends('layouts.secured_header')
@section('title')
  {!! t('Dashboard') !!}
@stop
@section('content')
  {!! HTML::style('assets/css/slick.css')!!}
  {!! HTML::style('assets/css/slick-theme.css')!!}
  <div class="container-fluid contributorMain">
    <div class="row">
      <div class="col-md-12 text-center">
        <div class="companyName mt-5">{!! t('Contextionary') !!}</div>
        @if(Auth::user()->hasRole(Config::get('constant.contributorRole')))
          <div class="contributorSlider">
            <div>
              <div class="sliderBlock">
                <h2>{!! t('Welcome') !!} {!! Auth::user()->first_name !!}</h2>
                <p>{!! t('Dashboard Slide 1 Text')!!}</p>
              </div>
            </div>
            <div>
              <div class="sliderBlock">
                <h2>{!! t('Contribute') !!}</h2>
                <p>{!! t('Dashboard Slide 2 Text')!!}</p>
                <ul class="iconsListing">
                  <li>
                    <div class="circle">
                      <img src="{!! asset('assets/images/slider-icon1.png') !!}">
                    </div>
                  </li>

                  <li>
                    <div class="circle">
                      <img src="{!! asset('assets/images/slider-icon2.png') !!}">
                    </div>
                  </li>

                  <li>
                    <div class="circle">
                      <img src="{!! asset('assets/images/slider-icon3.png') !!}">
                    </div>
                  </li>
                </ul>
              </div>
            </div>
            <div>
              <div class="sliderBlock">
                <h2>{!! t('slide 3 header') !!}</h2>
                <p>{!! t('Dashboard Slide 3 Text')!!}</p>
              </div>
            </div>
          </div>
        @else
          <div class="contributorSlider">
            <div>
              <div class="sliderBlock">
                <h2>{!! t('Welcome') !!} {!! Auth::user()->first_name !!}</h2>
                <p>{!! t('User Dashboard Slide 1 Text')!!}</p>
              </div>
            </div>
            <div>
              <div class="sliderBlock">
                <h2>{!! t('Contribute') !!}</h2>
                <p>{!! t('User  Dashboard Slide 2 Text')!!}</p>
              </div>
            </div>
            <div>
              <div class="sliderBlock">
                <h2>{!! t('user slide 3 header') !!}</h2>
                <p>{!! t('User  Dashboard Slide 3 Text')!!}</p>
              </div>
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
  {!! HTML::script('assets/js/slick.js') !!}
  {!! HTML::script('assets/js/user/dashboard.js') !!}
@endsection
