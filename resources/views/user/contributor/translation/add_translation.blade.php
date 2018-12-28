@extends('layouts.secured_header')
@section('title')
    {!! t('Add a Translation') !!}
@stop
@section('content')
    <div class="container-fluid contributorMain definePage" style="background: url({!! Storage::disk(Config::get('constant.Storage'))->url('Contexts') !!}/{!! $data['context_picture'] !!}); background-size:cover">
        <div class="wrapperMask"></div>
        @include('layouts.flc_header')
        <div class="row">
            @include('layouts.toaster')
        </div>
        <div class="row mt-4 contextRow">
            <div class="col-md-3">
                <p class="whiteText"><strong>{!! t('Context') !!}:</strong> &nbsp;{!! t($data['context_name']) !!}</p>
            </div>
            <div class="col-md-4 text-center">
                <p class="whiteText"><strong>{!! t('Phrase') !!}:</strong> &nbsp;{!! t($data['phrase_text']) !!}</p>
            </div>
            <div class="col-md-5 selectPhrase text-right">
                <span class="whiteText"><strong>{!! t('Phrase Type') !!}:</strong> @if($data['phrase_type']) <span class="whiteText">{!! Config::get('constant.PhraseType.'.$data['phrase_type']) !!}</span>
                    @endif</span>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-8">
                <label class="customLabel">{!! t('Phrase Meaning') !!}</label>
                <div class="voteMeaningBg defineMeaningBackground">
                    <div class="tab-content contextContent ">
                        <div id="tab1" class="tab-pane mCustomScrollbar fade show active">
                            <p>{!! $data['meaning'] !!}</p>
                        </div>
                    </div>
                </div>
                <label class="customLabel mt-1">{!! t('Author: ') !!}<span class="text-capitalize">{!! $data['writer'] !!}</span></label>
            </div>
            <div class="col-md-4 text-center">
                <label class="customLabel">{!! t('Phrase Illustration') !!}</label>

                <div class="uploadImgHolder mr-2">
                    <img src="{!! asset('storage') !!}/{!! $data['illustrator']['illustrator'] !!}" id="profile-img-tag" />
                </div>
                <label class="customLabel mt-5">{!! t('Author: ') !!}<span class="text-capitalize">{!! $data['illustrator']['illustrator_writer'] !!}</span></label>
            </div>
            @if(empty($data['translation']))
                <div class="col-md-12">
                    {!! Form::open(['url'=>lang_route('postTranslate'),'class'=>'','enctype'=>'multipart/form-data','method'=>'post', 'id'=>'form-submission']) !!}

                    @include('user.contributor.translation')
                    {!! Form::hidden('context_id', $data['context_id'], []) !!}
                    {!! Form::hidden('phrase_id', $data['phrase_id'], []) !!}


                    <div class="col-md-12 mt-4 text-center actionsBtn pb-4">
                        <a href="{!! URL::previous() !!}" class="orangeBtn ml-3 waves-light">{!! t('Return') !!}</a>
                        <button class="orangeBtn ml-3 waves-light">{!! t('Save') !!}</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            @endif
        </div>
        @if(!empty($data['translation']))
            <div class="hide_form">
                <div class="row mt-4">
                    <div class="col-md-12">

                        {!! Form::open(['url'=>lang_route('postTranslate'),'class'=>'','enctype'=>'multipart/form-data','method'=>'post', 'id'=>'form-submission']) !!}
                        @include('user.contributor.translation')
                        {!! Form::hidden('context_id', $data['context_id'], []) !!}
                        {!! Form::hidden('phrase_id', $data['phrase_id'], []) !!}
                        {!! Form::hidden('id', $data['translation']['id'], []) !!}

                    </div>
                </div>
                <div class="col-md-12 mt-4 text-center actionsBtn pb-4">
                    <a href="{!! URL::previous() !!}" class="orangeBtn ml-3 waves-light">{!! t('Return') !!}</a>
                    <button class="orangeBtn ml-3 waves-light">{!! t('Update') !!}</button>
                </div>
                {!! Form::close() !!}
            </div>
            <div class="show-bid">
                <div class="row">
                    <div class="col-md-12">
                        <label class="customLabel mt-1"><strong>{!! t('Phrase Translation') !!}: </strong> {!! $data['translation']['phrase_translation'] !!}</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label class="customLabel mt-1"><strong>{!! t('Meaning Translation') !!}</strong></label>
                    </div>
                </div>
                <div class="voteMeaningBg defineMeaningBackground">
                    <div class="tab-content contextContent ">
                        <div id="tab1" class="tab-pane mCustomScrollbar fade show active">
                            <p>{!! $data['translation']['translation'] !!}</p>
                        </div>
                    </div>
                </div>
                @if($data['translation']['coins'])
                    <div class="row ">
                        <div class="col-md-12">
                            <div class="coinsWrapper">
                                <span class="white-text">{!! t('Bid') !!}:  <span class="green-color">{!! $data['translation']['coins'] !!} {!! t('Coins') !!}</span></span>
                            </div>
                        </div>
                    </div>
                @endif
                @if($data['close_bid']!='1')
                    {!! Form::open(['url'=>lang_route('applyBidding'), 'method'=>'post', 'id'=>'bid-submission']) !!}
                    @include('user.contributor.bid')
                    {!! Form::hidden('context_id', $data['context_id'], []) !!}
                    {!! Form::hidden('phrase_id', $data['phrase_id'], []) !!}
                    {!! Form::hidden('model', 'translationRepo', []) !!}
                    {!! Form::hidden('type', env("TRANSLATE"), []) !!}
                    {!! Form::hidden('route', 'translate', []) !!}
                    {!! Form::hidden('meaning_id', $data['translation']['id'], []) !!}
                    {!! Form::close() !!}
                @elseif($data['close_bid']=='1')
                    <div class="row mt-4">
                        <div class="col-md-12 mt-4 text-center actionsBtn bid-div">
                            <a href="{!! URL::previous() !!}" class="orangeBtn ml-3 waves-light bidBtn">{!! t('Return') !!}</a>
                        </div>
                    </div>
                @endif
            </div>
        @endif

    </div>
    {!! HTML::script('assets/js/login.js') !!}
@endsection
