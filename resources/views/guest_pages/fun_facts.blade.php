@extends('layouts.secured_header')
@section('title')
    {!! t('Fun Facts') !!}
@stop
@section('content')
    <div class="container-fluid contributorMain funfact">
        @include('layouts.flc_header')
        <div class="row mt-4">
            <div class="col-md-12">
                @if(!$fun_facts->isEmpty())
                    @foreach($fun_facts as $record)
                        <div class="funFact-listing">
                            <div class="img-holder">
                                <img src="{!! asset('storage/'.$record['thumbnail']) !!}">
                            </div>
                            <div class="info">
                                <h3>{!! $record['title'] !!}</h3>
                                {!! substr($record['description'],0,150) !!}
                            </div>
                            <a href="{!! lang_url('fun-facts', ['id'=>$record['id']]) !!}" class="readMore"><img src="{!! asset('assets/images/readMore.png') !!}"></a>
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
