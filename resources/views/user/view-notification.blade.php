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
                        {!! html_entity_decode($notification) !!}
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