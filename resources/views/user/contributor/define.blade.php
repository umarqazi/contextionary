@extends('layouts.secured_header')
@section('title')
    {!! t('Define a Meaning') !!}
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
                        <a @if($context['status']!='disabled') href="{!! lang_route('defineMeaning', ['context_id'=>$context['context_id'],'phrase_id'=>$context['phrase_id']]) !!}" @endif>
                            <div class="categeoryBlock">
                                <div class="mask"></div>
                                <img src="{!! Storage::disk('local')->url('Contexts') !!}/{!! $context['context_picture'] !!}" class="mainImg">
                                <div class="info">
                                    <h1>{!! t($context['context_name']) !!} </h1>
                                    <p>{!! t($context['phrase_text']) !!}</p>
                                    {!! t('Open') !!} <i class="fa fa-angle-right"></i>
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
