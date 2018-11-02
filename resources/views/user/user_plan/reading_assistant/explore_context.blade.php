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

        <div class="col-sm-12 mt-3">
            @include('user.user_plan.reading_assistant.context_search')
        </div>

        <div class="col-lg-12 col-md-6 mt-5">
            <div class="row">
                @if(!$contexts->isEmpty())
                    @php
                        $firstLetter    = '';
                        $previous       = '';
                    @endphp
                    @foreach( $contexts as $context)
                        @php
                            $firstLetter = substr($context->context_name, 0, 1);
                            if($previous != $firstLetter){
                                echo '<div class="col-md-12 mt-4"><h2>'.$firstLetter.'</h2></div>';
                            }
                            $previous = $firstLetter;
                        @endphp
                        <div class="col-md-4">
                            <div class="phrase-body mb-0">
                                <p class="text-white"><a href="{!! lang_url('explore-context', ['context'=>$context->context_id]) !!}">{{$context->context_name}}</a></p>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-md-12">
                        <div class="text-center">
                            <strong class="record-message">No such Word in Record!</strong>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection