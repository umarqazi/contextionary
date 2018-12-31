@extends('layouts.secured_header')
@section('title')
    {!! t('Explore Context') !!}
@stop
@section('content')
<div class="container-fluid contributorMain userExploreWord userExploreContext2">
    @include('layouts.flc_header')
    <div class="row mt-4">
        @if(!isset($type))
            <div class="col-sm-6">
                <div class="exploreTitle">{{t('Explore a context')}}</div>
            </div>
        @else
            <div class="col-sm-6">
                <div class="exploreTitle">{{t('Contexts for')}} {{$phrase->phrase_text}}</div>
            </div>
        @endif
        <div class="col-sm-6 text-right">
            <a href="{!! lang_route('l-center') !!}" class="orangeBtn">Back</a>
        </div>
        @if(!isset($type))
            <div class="col-sm-12 mt-3">
                @include('user.user_plan.learning_center.context_search')
            </div>
        @endif

        <div class="col-lg-12 col-md-6 mt-5">
            <div class="row">
                @if(!$contexts->isEmpty())
                    @php
                        $firstLetter    = '';
                        $previous       = '';
                    @endphp
                    @foreach( $contexts as $context)
                        @php
                            $firstLetter = substr($context['context_name'], 0, 1);
                            if($previous != $firstLetter){
                                echo '<div class="col-md-12 mt-4"><h2>'.$firstLetter.'</h2></div>';
                            }
                            $previous = $firstLetter;
                        @endphp
                        <div class="col-md-4">
                            <div class="phrase-body mb-0">
                                @if(isset($type))
                                    @if($type == 'phares_context')
                                        <p class="text-white text-capitalize"><a href="{!! lang_url('learning-center/explore-context-phrase', ['context'=> $context['context_id'], 'phrase'=>$phrase->phrase_id ]) !!}">{{ucfirst($context['context_name'])}}</a></p>
                                    @else
                                        <p class="text-white text-capitalize"><a href="{!! lang_url('learning-center/explore-context', ['context'=>$context['context_id']]) !!}">{{ucfirst($context['context_name'])}}</a></p>
                                    @endif
                                @else
                                    <p class="text-white text-capitalize"><a href="{!! lang_url('learning-center/explore-context', ['context'=>$context['context_id']]) !!}">{{ucfirst($context['context_name'])}}</a></p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-md-12">
                        <div class="text-center">
                            <strong class="record-message">{!! t('No such Word in Record!') !!}</strong>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-md-12 mt-4 text-center">
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    {{$contexts->links()}}
                </div>
                <div class="col-md-4"></div>
            </div>
        </div>
    </div>
</div>
@endsection