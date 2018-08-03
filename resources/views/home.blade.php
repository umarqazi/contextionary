@extends('layouts.secured_header')
@section('title')
{!! t('Dashboard') !!}
@stop
@section('content')
{!! HTML::style('assets/owl-carousel/owl.carousel.css')!!}
<div class="container-fluid contributorMain">
  <div class="row">
    <div class="col-md-12 text-center">
      <div class="companyName mt-5">{!! t('Contextionary') !!}</div>
      <div class="sliderContainer">
        <div id="owl-demo" class="owl-carousel">
          <div class="item">
            <div class="sliderBlock">
              <h2>{!! t('Welcome') !!} {!! Auth::user()->first_name !!}</h2>
              <p>{!! t('Dashboard Slide 1 Text')!!}</p>
            </div>
          </div>
          <div class="item">
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
          <div class="item">
            <div class="sliderBlock">
              <h2>{!! t('Welcome') !!} {!! Auth::user()->first_name !!}</h2>
              <p>{!! t('Dashboard Slide 2 Text')!!}</p>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
{!! HTML::script('assets/owl-carousel/owl.carousel.js') !!}
{!! HTML::script('assets/js/user/dashboard.js') !!}
@endsection
