@extends('layouts.secured_header')
@section('title')
    {!! t('Explore Word') !!}
@stop
@section('content')
<div class="container-fluid contributorMain userExploreWord userExploreContext2">
    @include('layouts.flc_header')
    <div class="row mt-4">
        <div class="col-sm-6">
            <div class="exploreTitle">Explore a context</div>
        </div>
        <div class="col-sm-6 text-right">
            <a href="{!! lang_route('l-center') !!}" class="orangeBtn">Back</a>
        </div>

        {{--<div class="col-sm-12 mt-3">--}}
            {{--@include('user.user_plan.reading_assistant.context_search')--}}
        {{--</div>--}}

        <div class="col-lg-12 col-md-6 mt-5">
            <div class="row">
                <div class="col-md-12 mt-4">
                    <h2>{{$context}}</h2>
                </div>
                @foreach( $phrases as $phrase)
                    <div class="col-md-4">
                        <div class="phrase-body mb-0">
                            <p class="text-white"><a href="{!! lang_url('explore-context', ['context'=> $context_id, 'phrase'=>$phrase->phrases->phrase_id ]) !!}">{{ucfirst($phrase->phrases->phrase_text)}}</a></p>
                        </div>
                    </div>
                @endforeach
                <div class="col-md-12 mt-4 text-center">
                    <div class="row">
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                            {{$phrases->links()}}
                        </div>
                        <div class="col-md-4"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection