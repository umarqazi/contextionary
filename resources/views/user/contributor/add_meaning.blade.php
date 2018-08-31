@extends('layouts.secured_header')
@section('title')
    {!! t('Define a Meaning') !!}
@stop
@section('content')
    <div class="container-fluid contributorMain definePage" style="background: url({!! Storage::disk('local')->url('Contexts') !!}/{!! $data['context_picture'] !!}); background-size:cover">
        <div class="wrapperMask"></div>
        <div class="row">
            @include('user.contributor.contributor_heading')
        </div>
        {!! Form::open(['url'=>lang_route('postContextMeaning'), 'method'=>'post']) !!}
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
                    {!! Form::select('phrase_type', Config::get('constant.PhraseType'), $data['phrase_type'], ['class'=>'form-control'])!!}
                    @if ($errors->has('phrase_type'))
                        <div class="help-block"><strong>{{ $errors->first('phrase_type') }}</strong></div>
                    @endif
                @endif
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-12">
                <label class="customLabel">Phrase Meaning</label>
                @if(!$data['id'])
                    {!! Form::textarea('meaning', $data['meaning'], ['class'=>'enter-phrase', 'readonly'=>'readonly' ,'placeholder'=>'Enter Phrase Meaning']) !!}
                    <p class="text-right white-text">{!! t('Characters') !!} 0/2500</p>
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
                @endif
                {!! Form::hidden('context_id', $data['context_id'], []) !!}
                {!! Form::hidden('phrase_id', $data['phrase_id'], []) !!}
                {!! Form::hidden('user_id', Auth::user()->id, []) !!}
            </div>
            @if(!$data['id'])
                <div class="col-md-12 mt-4 text-center actionsBtn">
                    <a href="{!! URL::previous() !!}" class="orangeBtn ml-3 waves-light">Return</a>
                    <button class="orangeBtn ml-3 waves-light">Save</button>
                </div>
            @endif
        </div>
        {!! Form::close() !!}
        @if($data['id'])
            {!! Form::open(['url'=>lang_route('applyBidding'), 'method'=>'post']) !!}
            <div class="row mt-4">
                <div class="col-md-12 mt-4 text-center actionsBtn">
                    <div class="coinsWrapper">
                        <span class="white-text">{!! t('Coins') !!}</span>
                        <button type="button" class="sub"><i class="fa fa-minus"></i></button>
                        {!! Form::number('bid', '1',['class'=>'coins', 'min'=>'1']) !!}
                        {!! Form::hidden('meaning_id', $data['id'], []) !!}
                        <button type="button" class="add"><i class="fa fa-plus"></i></button>
                        @if ($errors->has('coins'))
                            <div class="help-block"><strong>{{ $errors->first('coins') }}</strong></div>
                        @endif
                    </div>
                    <button type="submit" class="orangeBtn ml-3 waves-light bidBtn">{!! t('Bid') !!}</button>
                </div>
            </div>
            {!! Form::close() !!}
        @endif
    </div>
@endsection
