@extends('layouts.secured_header')
@section('title')
    {!! t('Enter a Meaning') !!}
@stop
@section('content')
    <div class="container-fluid contributorMain definePage" style="background: url({!! Storage::disk(Config::get('constant.Storage'))->url('Contexts') !!}/{!! $data['context_picture'] !!}); background-size:cover">
        <div class="wrapperMask"></div>
        @include('layouts.flc_header')
        {!! Form::open(['url'=>lang_route('postContextMeaning'), 'method'=>'post', 'id'=>'form-submission']) !!}
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
                @if(!$data['phrase_type'])
                    {!! Form::select('phrase_type', Config::get('constant.PhraseType'), $data['phrase_type'], ['class'=>'customSelect'])!!}
                    @if ($errors->has('phrase_type'))
                        <div class="help-block text-right"><strong>{{ $errors->first('phrase_type') }}</strong></div>
                    @endif
                @endif
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-12">
                <label class="customLabel">{!! t('Phrase Meaning') !!}</label>
                @if(!$data['id'])
                    {!! Form::textarea('meaning', $data['meaning'], ['maxlength'=>"2500",'id'=>'meaning-area','class'=>'enter-phrase' ,'placeholder'=>'Enter Phrase Meaning']) !!}
                    <p class="text-right white-text"><span id="count">{!! t('Characters') !!} {!! strlen(Input::old('meaning')) !!}/2500</span></p>
                    @if ($errors->has('meaning'))
                        <div class="help-block"><strong>{{ $errors->first('meaning') }}</strong></div>
                    @endif
                @endif
                @if($data['id'])
                    <div class="voteMeaningBg defineMeaningBackground">
                        <div class="tab-content contextContent ">
                            <div id="tab1" class="tab-pane mCustomScrollbar fade show active">
                                <p>{!! $data['meaning'] !!}</p>
                            </div>
                        </div>
                    </div>
                    @if($data['close_bid']==1)
                        <div class="row ">
                            <div class="col-md-12">
                                <div class="coinsWrapper">
                                    <span class="white-text">{!! t('Bid') !!}:  <span class="green-color">{!! $data['coins'] !!} {!! t('Coins') !!}</span></span>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
                {!! Form::hidden('context_id', $data['context_id'], []) !!}
                {!! Form::hidden('phrase_id', $data['phrase_id'], []) !!}
                {!! Form::hidden('user_id', Auth::user()->id, []) !!}
            </div>
            @if(!$data['id'])
                <div class="col-md-12 mt-4 text-center actionsBtn">
                    <a href="{!! URL::previous() !!}" class="orangeBtn ml-3 waves-light">{!! t('Return') !!}</a>
                    <button class="orangeBtn ml-3 waves-light">{!! t('Save') !!}</button>
                </div>
            @endif
        </div>
        {!! Form::close() !!}
        @if($data['close_bid']!=1)
            @if($data['id'] )
                {!! Form::open(['url'=>lang_route('applyBidding'), 'method'=>'post', 'id'=>'bid-submission']) !!}
                @include('user.contributor.bid')
                {!! Form::hidden('meaning_id', $data['id'], []) !!}
                {!! Form::hidden('context_id', $data['context_id'], []) !!}
                {!! Form::hidden('phrase_id', $data['phrase_id'], []) !!}
                {!! Form::hidden('model', 'defineMeaning', []) !!}
                {!! Form::hidden('type', env("MEANING"), []) !!}
                {!! Form::hidden('route', 'define', []) !!}
                {!! Form::close() !!}
            @endif
        @elseif($data['close_bid']==1)
            <div class="row mt-4">
                <div class="col-md-12 mt-4 text-center actionsBtn bid-div">
                    <a href="{!! URL::previous() !!}" class="orangeBtn ml-3 waves-light bidBtn">{!! t('Return') !!}</a>
                </div>
            </div>
        @endif
    </div>
    {!! HTML::script('assets/js/login.js') !!}
@endsection
