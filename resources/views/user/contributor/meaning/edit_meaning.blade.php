@extends('layouts.secured_header')
@section('title')
    {!! t('Define a Meaning') !!}
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
                <span class="whiteText"><strong>{!! t('Phrase Type') !!}:</strong> </span>
                </span>
                {!! Form::select('phrase_type', Config::get('constant.PhraseType'), $data['phrase_type'], ['class'=>'customSelect'])!!}
                @if ($errors->has('phrase_type'))
                    <div class="help-block"><strong>{{ $errors->first('phrase_type') }}</strong></div>
                @endif
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-12">
                <label class="customLabel">Phrase Meaning</label>
                {!! Form::textarea('meaning', $data['meaning'], ['id'=>'meaning-area','maxlength'=>'2500', 'class'=>'enter-phrase' ,'placeholder'=>'Example: A quarterback is an American football player who is positioned behind the offensive line and leads or coordinates the offensive play of the team.']) !!}
                <p class="text-right white-text" id="count">{!! t('Characters') !!} {!! strlen($data['meaning']) !!}/2500</p>
                @if ($errors->has('meaning'))
                    <div class="help-block"><strong>{{ $errors->first('meaning') }}</strong></div>
                @endif
                {!! Form::hidden('context_id', $data['context_id'], []) !!}
                {!! Form::hidden('phrase_id', $data['phrase_id'], []) !!}
                {!! Form::hidden('user_id', Auth::user()->id, []) !!}
                {!! Form::hidden('id', $data['id'], []) !!}
            </div>
            <div class="col-md-12 mt-4 text-center actionsBtn">
                <a href="{!! URL::previous() !!}" class="orangeBtn ml-3 waves-light">Return</a>
                <button class="orangeBtn ml-3 waves-light">{!! t('Update') !!}</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
    {!! HTML::script('assets/js/login.js') !!}
@endsection
