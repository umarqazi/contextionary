@extends('layouts.secured_header')
@section('title')
    {!! t($title) !!}
@stop
@section('content')
    <div class="container-fluid contributorMain userTutorial">
        @include('layouts.flc_header')
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="notification-content">
                    @if($notification != null)
                        <div class="row noti-logo">
                            <div class="col-md-12 mt-4 mb-4">
                                <div class="text-center">
                                    <a href="@if(Auth::check()) {!! lang_url('dashboard') !!} @else {!! lang_url('home') !!} @endif"> <img src="{!! asset('assets/images/logo2.png') !!}"> </a>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-10">
                                <h2>{{$title}}</h2>
                            </div>
                            <div class="col-md-2">
                                <p><small>{{$created_at->diffForHumans()}}</small></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3 notification-section">
                                {!! html_entity_decode($notification) !!}
                            </div>
                        </div>
                        <div class="row mb-4 mt-2">
                            <div class="col-md-12">
                                <p><strong>Regards</strong></p>
                                <p><strong>Contextionary</strong></p>
                            </div>
                        </div>
                        <div class="row noti-logo">
                            <div class="col-md-12 mt-4 mb-4">
                                <div class="text-center">
                                   <p>© 2019 Contextionary. All rights reserved.</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-md-12">
                            <div class="text-center">
                                <strong class="record-message">{!! t('No Content Found') !!}</strong>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
@endsection