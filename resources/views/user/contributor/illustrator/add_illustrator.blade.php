@extends('layouts.secured_header')
@section('title')
    {!! t('Add a Illustrator') !!}
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
            <div class="col-md-12">
                <label class="customLabel">{!! t('Phrase Meaning') !!}</label>
                <div class="voteMeaningBg defineMeaningBackground">
                    <div class="tab-content contextContent ">
                        <div id="tab1" class="tab-pane mCustomScrollbar fade show active">
                            <p>{!! $data['meaning'] !!}</p>
                        </div>
                    </div>
                </div>
                <label class="customLabel float-right">{!! t('Written By: ') !!}{!! $data['writer'] !!}</label>
            </div>
        </div>
        @if(empty($data['illustrator']))
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="uploadImgHolder mr-2">
                        <img src="{!! asset('assets/images/dummy.png') !!}" id="profile-img-tag" />
                    </div>
                    {!! Form::open(['url'=>lang_route('postIllustrate'),'class'=>'illustrator-form','enctype'=>'multipart/form-data','method'=>'post', 'id'=>'form-submission']) !!}
                    <label class="customLabel">{!! t('Upload Illustration against this Meaning') !!}</label>
                    <label class="orangeBtn waves-light bidBtn">
                        {!! t('Browse') !!}
                        <input type="file" name="illustrate" id="profile-img" style="display: none">
                    </label>
                    @if ($errors->has('illustrate'))
                        <div class="help-block"><strong>{{ $errors->first('illustrate') }}</strong></div>
                    @endif
                    <div class="help-block"><strong class="hide-div" id="show-error">{!! t('Wrong Extension') !!}</strong></div>
                    {!! Form::hidden('context_id', $data['context_id'], []) !!}
                    {!! Form::hidden('phrase_id', $data['phrase_id'], []) !!}

                </div>
            </div>
            <div class="col-md-12 mt-4 text-center actionsBtn pb-4">
                <a href="{!! URL::previous() !!}" class="orangeBtn ml-3 waves-light">{!! t('Return') !!}</a>
                <button class="orangeBtn ml-3 waves-light disabled-save">{!! t('Save') !!}</button>
            </div>
            {!! Form::close() !!}
        @endif
        @if(!empty($data['illustrator']))
            <div class="hide_form">
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="uploadImgHolder mr-2">
                            <img src="{!! asset('assets/images/dummy.png') !!}" id="profile-img-tag" />
                        </div>
                        {!! Form::open(['url'=>lang_route('postIllustrate'),'class'=>'illustrator-form','enctype'=>'multipart/form-data','method'=>'post', 'id'=>'form-submission']) !!}
                        <label class="customLabel">{!! t('Upload Illustrator against this Meaning') !!}</label>
                        <label class="orangeBtn waves-light bidBtn">
                            {!! t('Browse') !!}
                            <input type="file" name="illustrate" id="profile-img" style="display: none">
                        </label>
                        @if ($errors->has('illustrate'))
                            <div class="help-block"><strong>{{ $errors->first('illustrate') }}</strong></div>
                        @endif
                        {!! Form::hidden('context_id', $data['context_id'], []) !!}
                        {!! Form::hidden('phrase_id', $data['phrase_id'], []) !!}
                        {!! Form::hidden('id', $data['illustrator']['id'], []) !!}
                        <div class="help-block"><strong class="hide-div" id="show-error">{!! t('Wrong Extension') !!}</strong></div>
                    </div>
                </div>
                <div class="col-md-12 mt-4 text-center actionsBtn pb-4">
                    <a href="{!! URL::previous() !!}" class="orangeBtn ml-3 waves-light">{!! t('Return') !!}</a>
                    <button class="orangeBtn ml-3 waves-light disabled-save">{!! t('Update') !!}</button>
                </div>
                {!! Form::close() !!}
            </div>
            <div class="show-bid">
                <div class="col-md-12">
                    <div class="uploadImgHolder mr-2">
                        <img src="{!! asset('storage') !!}/{!! $data['illustrator']->illustrator !!}" id="profile-img-tag" />
                    </div>
                </div>
                @if($data['illustrator']->coins)
                    <div class="col-md-12">
                        <div class="coinsWrapper">
                            <span class="white-text">{!! t('Bid') !!}:  <span class="green-color">{!! $data['illustrator']->coins !!} {!! t('Coins') !!}</span></span>
                        </div>
                    </div>
                @endif
                @if($data['close_bid']!='1')
                    {!! Form::open(['url'=>lang_route('applyBidding'), 'method'=>'post', 'id'=>'bid-submission']) !!}
                    @include('user.contributor.bid')
                    {!! Form::hidden('meaning_id', $data['illustrator']['id'], []) !!}
                    {!! Form::hidden('context_id', $data['context_id'], []) !!}
                    {!! Form::hidden('phrase_id', $data['phrase_id'], []) !!}
                    {!! Form::hidden('model', 'illustrate', []) !!}
                    {!! Form::hidden('type', env("ILLUSTRATE"), []) !!}
                    {!! Form::hidden('route', 'illustrate', []) !!}
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
