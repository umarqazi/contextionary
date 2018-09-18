@extends('layouts.secured_header')
@section('title')
    {!! t($data['title']) !!}
@stop
@section('content')
    <div class="container-fluid">
        @include('layouts.flc_header')
        <div class="row">
            @include('layouts.toaster')
        </div>
        <div class="row">
            @if(!$contextList->isEmpty())
                @foreach($contextList as $context)
                    <div class="col-sm-6 col-md-4 col-lg-4">
                        <a @if($context['status']!=Config::get('constant.phrase_status.submitted')) href="{!! lang_route($data['route'], ['context_id'=>$context['context_id'],'phrase_id'=>$context['phrase_id']]) !!}" @endif>
                            <div class="categeoryBlock">
                                <div class="mask"></div>
                                <?php $thumb=public_path().'/storage/Contexts/'.$context['context_picture'];?>
                                @if(file_exists($thumb))
                                    <img src="{!!asset('storage/Contexts') !!}/{!! $context['context_picture'] !!}" class="mainImg">
                                @else
                                    <img src="{!!asset('assets/images/dummy.png') !!}" class="mainImg">
                                @endif
                                <div class="info">
                                    <h1>@if($context['context_name']) {!! t($context['context_name']) !!} @endif </h1>
                                    <p>@if($context['phrase_text']){!! t($context['phrase_text']) !!} @endif</p>
                                    <strong class="{!! $context['status'] !!}"> {!! t($context['status']) !!} </strong> <i class="fa fa-angle-right"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            @else
                <div class="col-md-12">
                    <div class="text-center">
                        <strong class="record-message">{!! t('No Phrase available') !!}</strong>
                    </div>
                </div>
            @endif
            <div class="col-md-12 mt-4 mb-4 text-center">
                <div class="customPagination">
                    {!! $contextList->links() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
