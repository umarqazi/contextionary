@extends('layouts.secured_header')
@section('title')
    {!! t('Fun Facts') !!}
@stop
@section('content')
    <div class="container-fluid contributorMain funfact">
        <div class="row">
            @include('layouts.flc_header')

        </div>
        <div class="row mt-4">
            <div class="col-md-12">
                @if(!$getFunFacts->isEmpty())
                    @foreach($getFunFacts as $record)
                        <div class="funFact-listing">
                            <div class="img-holder">
                                <img src="{!! asset('assets/images/funfact-img1.jpg') !!}">
                            </div>
                            <div class="info">
                                <h3>{!! $record['title'] !!}</h3>
                                <p>{!! $record['description'] !!}</p>
                            </div>
                            <a href="#" class="readMore"><img src="{!! asset('assets/images/readMore.png') !!}"></a>
                        </div>
                    @endforeach
                @else
                    <div class="text-center">
                        <strong class="record-message">{!! t('Record not Found') !!}</strong>
                    </div>
                @endif

            </div>
        </div>
    </div>
    {!! HTML::script('assets/js/contactus.js') !!}
@endsection
