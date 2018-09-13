@extends('layouts.secured_header')
@section('title')
    {!! t('Illustrate') !!}
@stop
@section('content')
    <div class="container-fluid">
        @include('layouts.flc_header')
        <div class="row">
            @include('layouts.toaster')
        </div>
        <div class="row">
            @if($contextList)
                @foreach($contextList as $context)
                    <div class="col-sm-6 col-md-4 col-lg-4">
                        <a @if($context['status']=='Open') href="{!! lang_route('addIllustrate', ['context_id'=>$context['context_id'],'phrase_id'=>$context['phrase_id']]) !!}" @endif>
                            <div class="categeoryBlock">
                                <div class="mask"></div>
                                <img src="{!!asset('storage/Contexts') !!}/{!! $context['context']['context_picture'] !!}" class="mainImg">
                                <div class="info">
                                    <h1>@if($context['context']['context_name']) {!! t($context['context']['context_name']) !!} @endif </h1>
                                    <p>@if($context['context']['phrase_text']){!! t($context['context']['phrase_text']) !!} @endif</p>
                                    <strong class="{!! $context['status'] !!}"> {!! t($context['status']) !!} </strong> <i class="fa fa-angle-right"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            @endif
            <div class="col-md-12 mt-4 mb-4 text-center">
                <div class="customPagination">
                    {!! $contextList->links() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
