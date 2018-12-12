@extends('layouts.secured_header')
@section('title')
    {!! t('Vote Meaning') !!}
@stop
@section('content')
    <div class="container-fluid contributorMain voteMeaningBg" style="background: url({!! Storage::disk(Config::get('constant.Storage'))->url('Contexts') !!}/{!! $phraseMeaning['context_picture'] !!}); background-size:cover">
        <div class="wrapperMask"></div>
        @include('layouts.flc_header')
        <div class="row">
            @include('layouts.toaster')
        </div>
        @if($phraseMeaning)
            <div class="row mt-4 contextRow">
                <div class="col-md-4">
                    <p class="whiteText"><strong>{!! t('Context') !!}:</strong> {!! $phraseMeaning['context_name'] !!}</p>
                </div>

                <div class="col-md-4 text-center">
                    <p class="whiteText"><strong>{!! t('Phrase') !!}:</strong> {!! $phraseMeaning['phrase_text'] !!}</p>
                </div>

            </div>
            {!! Form::open(['url'=>lang_route('vote'), 'method'=>'post', 'id'=>'form-submission']) !!}
            <div class="row mt-4">
                <div class="col-md-8">
                    @if ($errors->has('meaning'))
                        <div class="help-block"><strong>{{ t($errors->first('meaning')) }}</strong></div>
                    @endif
                    <ul class="nav nav-pills contextListing" id="radio-check">
                        @if($phraseMeaning)
                            @foreach($phraseMeaning['allMeaning'] as $key=>$meaning)
                                <li data-tab="tab-{!! $key+1 !!}" class="@if(old('meaning')==$meaning['id']) active @endif">
                                    <label class="coin"><input type="radio" name="meaning" @if(old('meaning')==$meaning['id']) checked @endif value="{!! $meaning['id'] !!}"> </label>
                                    <a href="#tab{!! $key+1 !!}" data-toggle="pill">{!! t('Meaning') !!} {!! $key+1 !!}
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                    <div class="col-md-12">
                        <div class="row">
                            <p class="whiteText"><strong>{!! t('Phrase Type') !!}:</strong> {!! Config::get('constant.PhraseType.'.$phraseMeaning['phrase_type']) !!}</p>
                        </div>
                    </div>
                    <div class="tab-content contextContent ">
                        @if($phraseMeaning)
                            @foreach($phraseMeaning['allMeaning'] as $key=>$meaning)
                                <div id="tab-{!! $key+1 !!}" class="tab-pane mCustomScrollbar fade show @if(old('meaning')==$meaning['id']) current @endif">
                                    <p>{!! $meaning['meaning'] !!}</p>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="text-center">
                        <a href="{!! URL::previous() !!}" class="orangeBtn waves-light mb-3 mr-3">{!! t('Return') !!}</a>
                        <button type="submit" class="orangeBtn waves-light mb-3 mr-3 @if(old('meaning')==NULL) grey @endif" @if(old('meaning')==NULL) disabled @endif id="submit-button">{!! t('Submit') !!}</button>
                        <a class="orangeBtn waves-light mb-3" data-toggle="modal" data-target="#pointModal">{!! t('Poor Quality') !!}</a>
                    </div>
                </div>

                <div class="col-md-4" id="rules">
                    <p class="white-text"><strong>{!! t('Qualifying Rules') !!}:</strong></p>
                    <div class="md-form ml-4 mt-0">
                        <div class="custom-control custom-checkbox">
                            {!! Form::checkbox('grammer', 1, null, ['class'=>'custom-control-input make-unchecked', 'id'=>'checkBox1']) !!}
                            <label class="custom-control-label" for="checkBox1">{!! t('No Grammar Error') !!}</label>
                        </div>
                        @if ($errors->has('grammer'))
                            <div class="help-block"><strong>{{ t($errors->first('grammer')) }}</strong></div>
                        @endif
                    </div>

                    <div class="md-form ml-4 mt-0">
                        <div class="custom-control custom-checkbox">
                            {!! Form::checkbox('spelling', 1, null, ['class'=>'custom-control-input make-unchecked', 'id'=>'checkBox2']) !!}
                            <label class="custom-control-label" for="checkBox2">{!! t('No Spelling Error') !!}</label>
                        </div>
                        @if ($errors->has('spelling'))
                            <div class="help-block"><strong>{{ t($errors->first('spelling')) }}</strong></div>
                        @endif
                    </div>

                    <div class="md-form ml-4 mt-0">
                        <div class="custom-control custom-checkbox">
                            {!! Form::checkbox('audience', 1, null, ['class'=>'custom-control-input make-unchecked', 'id'=>'checkBox3']) !!}
                            <label class="custom-control-label" for="checkBox3">{!! t('Intelligible to a non-expert audience') !!}</label>
                        </div>
                        @if ($errors->has('audience'))
                            <div class="help-block"><strong>{{ t($errors->first('audience')) }}</strong></div>
                        @endif
                    </div>
                    <div class="md-form ml-4 mt-0">
                        <div class="custom-control custom-checkbox">
                            {!! Form::checkbox('part_of_speech', 1, null, ['class'=>'custom-control-input make-unchecked', 'id'=>'checkBox4']) !!}
                            <label class="custom-control-label" for="checkBox4">{!! t('Part of speech correctly identified') !!}</label>
                        </div>
                        @if ($errors->has('part_of_speech'))
                            <div class="help-block"><strong>{{ t($errors->first('part_of_speech')) }}</strong></div>
                        @endif
                    </div>
                </div>
            </div>
            {!! Form::hidden('context_id', $phraseMeaning['context_id']) !!}
            {!! Form::hidden('phrase_id', $phraseMeaning['phrase_id']) !!}
            {!! Form::hidden('return_url', url()->previous()) !!}
            {!! Form::close() !!}
        @else:
        <div class="text-center">
            <strong class="record-message">{!! t('No Meaning available for Vote') !!}</strong>
        </div>
        @endif
    </div>
    <?php $context_id=$phraseMeaning['context_id']; $phrase_id=$phraseMeaning['phrase_id']; $type=env('MEANING')?>
    @include('user.contributor.confirmation')
    {!! HTML::script('assets/js/user/vote.js') !!}
@endsection
