@extends('layouts.secured_header')
@section('title')
    {!! t('Contact Us') !!}
@stop
@section('content')
    <script src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
    <div class="container-fluid contributorMain contactPage">
        <div class="row">
            @include('layouts.flc_header')
            <div class="col-md-12 mt-4">
                <div class="whiteBlock">
                    <div class="contactInfo">
                        <li>
                            <div class="img-holder"><img src="{!! asset('assets/images/map-icon.png') !!}"></div>
                            <div class="info">
                                <h2>Location</h2>
                                <p>{{$settings->where('keys', 'Address')->first()->values}}</p>
                            </div>
                        </li>
                        <li>
                            <div class="img-holder"><img src="{!! asset('assets/images/clock-icon.png') !!}"></div>
                            <div class="info">
                                <h2>Office hours</h2>
                                <p>
                                    {{$settings->where('keys', 'Office Hours Monday')->first()->values}}<br>
                                    {{$settings->where('keys', 'Office Hours Tuesday')->first()->values}}<br>
                                    {{$settings->where('keys', 'Office Hours Wednesday')->first()->values}}<br>
                                    {{$settings->where('keys', 'Office Hours Thursday')->first()->values}}<br>
                                    {{$settings->where('keys', 'Office Hours Friday')->first()->values}}
                                </p>
                            </div>
                        </li>

                        <li>
                            <div class="img-holder"><img src="{!! asset('assets/images/phone-icon.png') !!}"></div>
                            <div class="info">
                                <h2>email & phone</h2>
                                <p><a href="mailto:{{$settings->where('keys', 'Email')->first()->values}}">
                                        {{$settings->where('keys', 'Email')->first()->values}}
                                    </a>
                                </p>
                                <p>
                                    {{$settings->where('keys', 'Phone')->first()->values}}
                                </p>
                            </div>
                        </li>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            @include('layouts.toaster')
            <div class="col-lg-8 col-md-6">
                {!! Form::open(['method'=>'POST', 'url'=>lang_route('contactUs')]) !!}
                <div class="row contactForm">
                    <div class="col-md-6">
                        {!! Form::text('first_name', null, ['class'=>'fld', 'placeholder'=>t('First Name')]) !!}
                        @if ($errors->has('first_name'))
                            <div class="help-block"><strong>{{ $errors->first('first_name') }}</strong></div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        {!! Form::text('last_name', null, ['class'=>'fld', 'placeholder'=>t('Last Name')]) !!}
                        @if ($errors->has('last_name'))
                            <div class="help-block"><strong>{{ $errors->first('last_name') }}</strong></div>
                        @endif
                    </div>
                    <div class="col-md-12">
                        {!! Form::text('email', null, ['class'=>'fld', 'placeholder'=>t('Email')]) !!}
                        @if ($errors->has('email'))
                            <div class="help-block"><strong>{{ $errors->first('email') }}</strong></div>
                        @endif
                    </div>
                    <div class="col-md-12">
                        {!! Form::textarea('message', null, ['class'=>'fld textArea', 'placeholder'=>t('Comments or Questions')]) !!}
                        @if ($errors->has('message'))
                            <div class="help-block"><strong>{{ $errors->first('message') }}</strong></div>
                        @endif
                    </div>
                    <div class="col-md-12">
                        <p class="whiteText">{!! t('contact us form text') !!}</p>
                    </div>

                    <div class="col-md-12 text-center mb-5 mt-2">
                        <input type="submit" class="orangeBtn submit-button">
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
            <div class="col-lg-4 col-md-6">
                <div id="map" style="width:342px; height: 444px;">

                </div>
            </div>

        </div>
    </div>
    {!! HTML::script('assets/js/contactus.js') !!}
@endsection
