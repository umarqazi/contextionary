@extends('layouts.secured_header')
@section('title')
    {!! t('Vote Meaning') !!}
@stop
@section('content')
    <div class="container-fluid contributorMain voteMeaningBg">
        <div class="wrapperMask"></div>
        @include('layouts.flc_header')
        <div class="row">
            @include('layouts.toaster')
        </div>
        @if($illustrators)
            <div class="row mt-4 contextRow">
                <div class="col-md-3">
                    <p class="whiteText"><strong>{!! t('Context') !!}:</strong> {!! $illustrators['context_name'] !!}</p>
                </div>

                <div class="col-md-3 text-center">
                    <p class="whiteText"><strong>{!! t('Phrase') !!}:</strong> {!! $illustrators['phrase_text'] !!}</p>
                </div>
                <div class="col-md-4 text-center">
                    <p class="whiteText"><strong>{!! t('Author Name') !!}:</strong> {!! $illustrators['writer'] !!}</p>
                </div>

            </div>
            {!! Form::open(['url'=>lang_route('saveIllustratorVote'), 'method'=>'post']) !!}
            <div class="row mt-4">
                <div class="col-md-8">
                    @if ($errors->has('illustrator'))
                        <div class="help-block"><strong>{{ t($errors->first('illustrator')) }}</strong></div>
                    @endif
                    <div class="illustrator-div">
                        <div class="row">
                            @foreach($illustrators['illustrators'] as $illustrator)
                                <div class="col-sm-6 col-md-4 illustrators-active @if(old('illustrator')==$illustrator['id']) active-illustrator @endif">
                                    <div class="illustrations-block">
                                        <label class="coin illustrator"><input type="radio" name="illustrator" @if(old('illustrator')==$illustrator['id']) checked @endif value="{!! $illustrator['id'] !!}"> </label>
                                        <img src="{!! asset('storage') !!}/{!! $illustrator['illustrator'] !!}" class="main-img">
                                        <div class="mask"></div>
                                        <a class="fancybox" href="{!! asset('storage') !!}/{!! $illustrator['illustrator'] !!}" data-fancybox-group="gallery"><i class="fa fa-search"></i></a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="text-center">
                        <a href="#" class="orangeBtn waves-light mb-3 mr-3">{!! t('Return') !!}</a>
                        <button type="submit" class="orangeBtn waves-light mb-3 mr-3 @if(old('illustrator')==NULL) grey @endif" @if(old('illustrator')==NULL) disabled @endif id="submit-button">{!! t('Submit') !!}</button>
                        <a href="{!! lang_route('poor-quality', ['context_id'=>$illustrators['context_id'],'phrase_id'=>$illustrators['phrase_id'],'type'=>env('ILLUSTRATE')]) !!}" class="orangeBtn waves-light mb-3">{!! t('Poor Quality') !!}</a>
                    </div>
                </div>

                <div class="col-md-4">
                    <p class="white-text"><strong>{!! t('Qualifying Rules') !!}:</strong></p>
                    <div class="md-form ml-4 mt-0">
                        <div class="custom-control custom-checkbox">
                            {!! Form::checkbox('text_image', 1, null, ['class'=>'custom-control-input make-unchecked', 'id'=>'checkBox1']) !!}
                            <label class="custom-control-label" for="checkBox1">{!! t('No text image') !!}</label>
                        </div>
                        @if ($errors->has('text_image'))
                            <div class="help-block"><strong>{{ t($errors->first('text_image')) }}</strong></div>
                        @endif
                    </div>

                    <div class="md-form ml-4 mt-0">
                        <div class="custom-control custom-checkbox">
                            {!! Form::checkbox('negative_contation', 1, null, ['class'=>'custom-control-input make-unchecked', 'id'=>'checkBox2']) !!}
                            <label class="custom-control-label" for="checkBox2">{!! t('No negative connotation') !!}</label>
                        </div>
                        @if ($errors->has('negative_contation'))
                            <div class="help-block"><strong>{{ t($errors->first('negative_contation')) }}</strong></div>
                        @endif
                    </div>

                    <div class="md-form ml-4 mt-0">
                        <div class="custom-control custom-checkbox">
                            {!! Form::checkbox('ambiguous', 1, null, ['class'=>'custom-control-input make-unchecked', 'id'=>'checkBox3']) !!}
                            <label class="custom-control-label" for="checkBox3">{!! t('No ambiguous interpretation') !!}</label>
                        </div>
                        @if ($errors->has('ambiguous'))
                            <div class="help-block"><strong>{{ t($errors->first('ambiguous')) }}</strong></div>
                        @endif
                    </div>
                </div>
            </div>
            {!! Form::hidden('context_id', $illustrators['context_id']) !!}
            {!! Form::hidden('phrase_id', $illustrators['phrase_id']) !!}
            {!! Form::close() !!}
        @else:
        <div class="text-center">
            <strong class="record-message">{!! t('No Meaning available for Vote') !!}</strong>
        </div>
        @endif
    </div>
    {!! HTML::script('assets/js/user/vote.js') !!}
    {!! HTML::script('assets/source/jquery.fancybox.pack.js') !!}
    {!! HTML::script('assets/js/fancybox.js') !!}
@endsection
