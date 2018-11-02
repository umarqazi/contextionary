@extends('layouts.secured_header')
@section('title')
    {!! t('Tutorials') !!}
@stop
@section('content')
<div class="container-fluid contributorMain userTutorial">
    <div class="wrapperMask"></div>
    @include('layouts.flc_header')
    <div class="row mt-4">
        <div class="col-md-12 tut-div">
            @if($tutorial != null)
                {!! html_entity_decode($tutorial) !!}
            @else
                <div class="col-md-12">
                    <div class="text-center">
                        <strong class="record-message">{!! t('No Tutorials available') !!}</strong>
                    </div>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection