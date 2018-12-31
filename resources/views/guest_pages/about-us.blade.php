@extends('layouts.secured_header')
@section('title')
    {!! t('About Us') !!}
@stop
@section('content')
<div class="container-fluid contributorMain userTutorial">
    @include('layouts.flc_header')
    <div class="row mt-4">
        <div class="col-md-12 tut-div">
            @if($about_us != null)
                {!! html_entity_decode($about_us) !!}
            @else
                <div class="col-md-12">
                    <div class="text-center">
                        <strong class="record-message">{!! t('About Us') !!}</strong>
                    </div>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection