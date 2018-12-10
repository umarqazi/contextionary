@extends('layouts.secured_header')
@section('title')
    {!! t('List of Phrases') !!}
@stop
@section('content')
<div class="container-fluid contributorMain userExploreWord userExploreContext2">
    @include('layouts.flc_header')
    <div class="row mt-4">
        <div class="col-sm-6">
            @if($type == 'context_forwarded')
                <div class="exploreTitle">Explore a context</div>
            @else
                <div class="exploreTitle">Explore a word</div>
            @endif
        </div>
        <div class="col-sm-6 text-right">
            @if(explode('/',Request::server('HTTP_REFERER'))[5] == 'explore-word')
                <a href="{!! lang_route('explore-word') !!}" class="orangeBtn">Back</a>
            @else
                <a href="{!! lang_route('explore-context') !!}" class="orangeBtn">Back</a>
            @endif
        </div>
        <div class="col-lg-12 col-md-6 mt-5">
            <div class="row">
                <div class="col-md-12 mt-4">
                    @if($type == 'context_forwarded')
                        <h2>{{$context}}</h2>
                    @else
                        <h2>List of Matches of "{{$phrases_searched}}"</h2>
                    @endif
                </div>
                @if(!$phrases->isEmpty())
                    @foreach( $phrases as $phrase)
                        @if($phrase->phrases != null && $type == 'context_forwarded')
                            <div class="col-md-4">
                                <div class="phrase-body mb-0">
                                    <p class="text-white"><a href="{!! lang_url('learning-center/explore-context', ['context'=> $context_id, 'phrase'=>$phrase->phrases->phrase_id ]) !!}">{{ucfirst($phrase->phrases->phrase_text)}}</a></p>
                                </div>
                            </div>
                        @elseif( $phrase->phrase_text != null && $type == 'phrase_forwarded')
                            <div class="col-md-4">
                                <div class="phrase-body mb-0">
                                    <p class="text-white"><a href="{!! lang_url('learning-center/explore-context-phrase', ['phrase'=>$phrase->phrase_id ]) !!}">{{ucfirst($phrase->phrase_text)}}</a></p>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @else
                <div class="col-md-12 mt-4 text-center">
                    <div class="text-center">
                        <strong class="record-message">{{t('No Phrase against this Context!')}}</strong>
                    </div>
                </div>
                @endif
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