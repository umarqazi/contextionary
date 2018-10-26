<label class="customLabel">{!! t('Phrase Translation') !!}</label>
{!! Form::text('phrase_translation', $data['translation']['phrase_translation'], ['class'=>'form-control' ,'placeholder'=>t('Phrase Translation')]) !!}
@if ($errors->has('phrase_translation'))
    <div class="help-block"><strong>{{ $errors->first('phrase_translation') }}</strong></div>
@endif
<label>&nbsp;</label>
<label class="customLabel">{!! t('Meaning Translation') !!}</label>
{!! Form::textarea('translation', $data['translation']['translation'], ['maxlength'=>"2500",'id'=>'meaning-area','class'=>'enter-phrase' ,'placeholder'=>t('Translate Phrase')]) !!}
<p class="text-right white-text"><span id="count">{!! t('Characters') !!} {!! ($data['translation']['translation'])? strlen($data['translation']['translation']):strlen(Input::old('translation')) !!}/2500</span></p>
@if ($errors->has('translation'))
    <div class="help-block"><strong>{{ $errors->first('translation') }}</strong></div>
@endif