@extends('layouts.secured_header')
@section('title')
    {!! t('Pictionary') !!}
@stop
@section('content')
    <div class="container-fluid contributorMain pictionaryQuizLanding resultPage">
        <div class="learningModule">
            <div class="wrapper">
                <div class="companyName">Pictionary</div>
                <p>{{$message}}</p>
            </div>
        </div>
    </div>
@endsection