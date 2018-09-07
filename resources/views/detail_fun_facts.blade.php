@extends('layouts.secured_header')
@section('title')
    {!! t('Fun Facts') !!}
@stop
@section('content')
    <div class="container-fluid contributorMain funfact-detail">
        <div class="row">
            @include('layouts.flc_header')

        </div>
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="blueHeading">{{ $fun_fact->title}} <a href="#" class="orangeBtn float-right waves-light">Back</a></div>
                <div class="funDetail mCustomScrollbar" data-mcs-theme="dark">
                    <div class="row">
                        <div class="col-md-12">
                            <p>{{$fun_fact->description}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! HTML::script('assets/js/contactus.js') !!}
@endsection
