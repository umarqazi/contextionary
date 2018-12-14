@extends('layouts.secured_header')
@section('title')
    {!! t('Context Finder') !!}
@stop
@section('content')
    <div class="container-fluid contributorMain definePage contextFinder">
        <div class="wrapperMask"></div>
        <div class="row">
            <div class="col-md-12">
                @include('layouts.flc_header')
            </div>

        </div>
        {!! Form::open(['url'=>lang_route('context-finder'), 'method'=>'post', 'id'=>'form-submission']) !!}
        <div class="row mt-4">
            <div class="col-md-12">
                <label class="customLabel pt-2 d-inline">Enter Your Text or Attach Document</label>
                <div class="float-right">
                    <label>
                        <input type="file" class="d-none" id='upload_file' onchange="onUpload()">
                        <span class="orangeBtn">upload document</span>
                    </label>
                </div>
                <div class="float-right">
                    <p id="error_upload" class="hidden bold red bc_none p10"></p>
                </div>
                {!! Form::textarea('context', null, ['id'=>'meaning-area','class'=>'enter-phrase', 'placeholder'=>'Enter Text', 'required' => true]) !!}
                @if ($errors->has('context'))
                    <div class="record-message text-center bold">{{ $errors->first('context') }}</div>
                @endif
                <p class="text-right white-text"><span id="count">{!! t('Characters:') !!} {!! strlen(Input::old('context')) !!}/2500</span></p>
            </div>

            <div class="col-md-12 mt-4 text-center actionsBtn">
                <button class="orangeBtn ml-3 waves-light">Submit</button>
                <a class="orangeBtn ml-3 waves-light" href="{{lang_url('context-finder')}}">Reset</a>
            </div>
        </div>
        {!! Form::close() !!}
        @if($flag)
            <div class="context-finder-content">
                <div class="exportRow">
                    <div class="col-md-6">
                        <span>Context</span>
                        <select class="selectOption" onchange="contextChange(this)">
                            @foreach( $context_list as $context)
                                <option value="{{$context['context_id']}}">{{$context['context_name']}}</option>
                            @endforeach
                        </select>
                    </div>
                    @if(!empty(Session::get('export_data')))
                        <div class="col-md-6 text-right">
                            <a href="{{ lang_route('text-test') }}" class="orangeBtn">Export report</a>
                        </div>
                    @endif

                </div>

                <div class="col-md-12">

                    @foreach( $context_list as $context_key => $context)
                    <div class="exportBlock exportBlock_{{$context['context_id']}} @if($context_key != 0 ) hidden @endif">
                            @php
                                // echo '<pre>';
                                // print_r($string);
                                // print_r($context['context_id']);
                                // die();
                            @endphp
                            {!! $string[$context['context_id']] !!}
                    </div>
                    @endforeach

                    <div class="contextTabs">
                        <ul class="nav nav-pills" role="tablist">
                            <li>
                                <a class="active" data-toggle="pill" href="#tab1">Text Key Words</a>
                            </li>
                            <li>
                                <a class="" data-toggle="pill" href="#tab2">Context Glossary</a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div id="tab1" class="tab-pane active">
                                <div class="table-responsive">
                                    <table class="table contextTable">
                                        <tbody>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right">
                                                <select class="languageBar" onchange="languageChange(this)">
                                                    <option value="fr">{{t('French')}}</option>
                                                    <option value="sp">{{t('Spanish')}}</option>
                                                    <option value="hi">{{t('Hindi')}}</option>
                                                    <option value="ch">{{t('Chinese')}}</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr class="tr-head">
                                            <td class="text-center">{{t('Jargon')}}</td>
                                            <td class="text-center">{{t('Meaning')}}</td>
                                            <td class="text-center">{{t('Illustration')}}</td>
                                            <td class="text-center">{{t('Related items')}}</td>
                                            <td class="text-center">{{t('Phrase translation')}}</td>
                                            <td class="text-right lastChild">
                                                {{t('Meaning translation')}}
                                            </td>
                                        </tr class="tr-head">
                                        @foreach( $context_list as $context_key => $context)
                                            @if (array_key_exists('phrases', $context))
                                                @foreach( $context['phrases'] as $phrase)
                                                @if($context_key != 0)
                                                    <tr class="tr-context tr-context-{{$context['context_id']}} hidden">
                                                @else
                                                    <tr class="tr-context tr-context-{{$context['context_id']}}">
                                                @endif
                                                <td class="text-center">{{ucwords($phrase['phrase'])}}</td>
                                                <td class="text-center">{{$phrase['meaning']}}</td>
                                                <td class="text-center">
                                                    @if($phrase['illustration'] != '')
                                                        <img src="{!! asset('storage/'.$phrase['illustration']) !!}" class="tableImg">
                                                    @else
                                                        <p>{{t('-')}}</p>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @php
                                                        $testing                = [];
                                                        $related_phrase_count   = 0;
                                                    @endphp
                                                    @foreach($phrase['related_phrase'] as $key=>$related_phrase)
                                                        @if( $related_phrase->relatedPhrases != null)
                                                            @if(!empty($related_phrase->relatedPhrases->phrase_text))
                                                                @php
                                                                    $related_phrase_count = $related_phrase_count + 1;
                                                                    $testing[$key]='<a href="#" onclick="openRelatedPhrase(event, '.$related_phrase->relatedPhrases->phrase_id.')">'.ucwords($related_phrase->relatedPhrases->phrase_text).'</a>';
                                                                @endphp
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                    {!! implode(', ', $testing) !!}
                                                    @if( ! $related_phrase_count > 0)
                                                        <p>{{t('-')}}</p>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if(count($phrase['translation']) > 0)
                                                        @foreach($phrase['translation'] as $translation)
                                                            @if($translation['language'] == 'Spanish')
                                                                <p class="hidden phrase trans_sp">{{ $translation['phrase_translation'] }}</p>
                                                            @elseif($translation['language'] == 'Hindi')
                                                                <p class="hidden phrase trans_hi">{{ $translation['phrase_translation'] }}</p>
                                                            @elseif($translation['language'] == 'Chinese')
                                                                <p class="hidden phrase trans_ch">{{ $translation['phrase_translation'] }}</p>
                                                            @elseif($translation['language'] == 'French')
                                                                <p class="phrase trans_fr">{{ $translation['phrase_translation'] }}</p>
                                                            @endif
                                                        @endforeach
                                                        <p class="hidden phrase trans_none">{{t('-')}}</p>
                                                    @else
                                                        <p class="phrase trans_none">{{t('-')}}</p>
                                                    @endif
                                                </td>
                                                <td class="lastChild text-center">
                                                    @if(count($phrase['translation']) > 0)
                                                        @foreach($phrase['translation'] as $translation)
                                                            @if($translation['language'] == 'Spanish')
                                                                <p class="hidden phrase_meaning trans_sp">{{ $translation['translation'] }}</p>
                                                            @elseif($translation['language'] == 'Hindi')
                                                                <p class="hidden phrase_meaning trans_hi">{{ $translation['translation'] }}</p>
                                                            @elseif($translation['language'] == 'Chinese')
                                                                <p class="hidden phrase_meaning trans_ch">{{ $translation['translation'] }}</p>
                                                            @elseif($translation['language'] == 'French')
                                                                <p class="phrase_meaning trans_fr">{{ $translation['translation'] }}</p>
                                                            @endif
                                                        @endforeach
                                                            <p class="hidden phrase_meaning trans_none">{{t('-')}}</p>
                                                    @else
                                                        <p class="phrase_meaning trans_none">{{t('-')}}</p>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                            @endif
                                        @endforeach
                                            <tr class="tr-context tr-context-none hidden">
                                                <td colspan="6">
                                                    <p class="record-message">{{t('No Phrases for this Context!')}}</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div id="tab2" class="tab-pane fade">
                                <div class="table-responsive">
                                    <table class="table contextTable">
                                        <tbody>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td class="text-right">
                                                    <select class="languageBar" onchange="languageChange(this)">
                                                        <option value="fr">{{t('French')}}</option>
                                                        <option value="sp">{{t('Spanish')}}</option>
                                                        <option value="hi">{{t('Hindi')}}</option>
                                                        <option value="ch">{{t('Chinese')}}</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr class="tr-head">
                                                <td class="text-center">{{t('Jargon')}}</td>
                                                <td class="text-center">{{t('Meaning')}}</td>
                                                <td class="text-center">{{t('Illustration')}}</td>
                                                <td class="text-center">{{t('Related items')}} </td>
                                                <td class="text-center">{{t('Phrase Translation')}}</td>
                                                <td class="text-right lastChild">
                                                    {{t('Meaning Translation')}}
                                                </td>
                                            </tr>
                                            @foreach( $context_list as $context_key => $context)
                                                @if (array_key_exists('phrases', $context))
                                                    @foreach( $context['phrases'] as $phrase)
                                                        @foreach($phrase['related_phrase'] as $related_phrase)
                                                            @if($related_phrase['details']['phrase'] != '')
                                                            @if($context_key != 0)
                                                            <tr class="tr-context tr-context-{{$context['context_id']}} hidden">
                                                            @else
                                                            <tr class="tr-context tr-context-{{$context['context_id']}}">
                                                            @endif
                                                                <td class="text-center">
                                                                    {!!ucwords($related_phrase['details']['phrase'])!!}
                                                                </td>
                                                                <td class="text-center">
                                                                    {!! $related_phrase['details']['meaning'] !!}
                                                                </td>
                                                                <td class="text-center">
                                                                    @if($related_phrase['details']['illustration'] != '')
                                                                        <img src="{!! asset('storage/'.$related_phrase['details']['illustration']) !!}" class="tableImg">
                                                                    @else
                                                                        <p>{{t('-')}}</p>
                                                                    @endif
                                                                </td>
                                                                <td  class="text-center" id="related_phrase_td_{{$related_phrase['related_phrase_id']}}">
                                                                @if( count($related_phrase['details']['related_phrase']) > 0)
                                                                    @php
                                                                        $testing1              = [];
                                                                        $related_phrase_count1 = 0;
                                                                    @endphp
                                                                    @foreach($related_phrase['details']['related_phrase'] as $rel_phrase_key => $rel_phrase)
                                                                        @if( $rel_phrase->relatedPhrases != null)
                                                                            @if( $rel_phrase->relatedPhrases->phrase_text != '')
                                                                                @php
                                                                                    $related_phrase_count1 = $related_phrase_count1 + 1;
                                                                                    $testing1[$rel_phrase_key]=ucwords($rel_phrase->relatedPhrases->phrase_text);
                                                                                @endphp
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                    {!! implode(', ', $testing1) !!}
                                                                    @if( ! $related_phrase_count1 > 0)
                                                                        <p>{{t('-')}}</p>
                                                                    @endif
                                                                @endif
                                                                </td>
                                                                <td class="text-center">
                                                                    @if(count($related_phrase['details']['translation']) > 0)
                                                                        @foreach($related_phrase['details']['translation'] as $translation)
                                                                            @if($translation['language'] == 'Spanish')
                                                                                <p class="hidden phrase trans_sp">{{ $translation['phrase_translation'] }}</p>
                                                                            @elseif($translation['language'] == 'Hindi')
                                                                                <p class="hidden phrase trans_hi">{{ $translation['phrase_translation'] }}</p>
                                                                            @elseif($translation['language'] == 'Chinese')
                                                                                <p class="hidden phrase trans_ch">{{ $translation['phrase_translation'] }}</p>
                                                                            @elseif($translation['language'] == 'French')
                                                                                <p class="phrase trans_fr">{{ $translation['phrase_translation'] }}</p>
                                                                            @endif
                                                                        @endforeach
                                                                        <p class="hidden phrase trans_none">{{t('-')}}</p>
                                                                    @else
                                                                        <p class="phrase trans_none">{{t('-')}}</p>
                                                                    @endif
                                                                </td>
                                                                <td class="lastChild text-center">
                                                                    @if(count($related_phrase['details']['translation']) > 0)
                                                                        @foreach($related_phrase['details']['translation'] as $translation)
                                                                            @if($translation['language'] == 'Spanish')
                                                                                <p class="hidden phrase_meaning trans_sp">{{ $translation['translation'] }}</p>
                                                                            @elseif($translation['language'] == 'Hindi')
                                                                                <p class="hidden phrase_meaning trans_hi">{{ $translation['translation'] }}</p>
                                                                            @elseif($translation['language'] == 'Chinese')
                                                                                <p class="hidden phrase_meaning trans_ch">{{ $translation['translation'] }}</p>
                                                                            @elseif($translation['language'] == 'French')
                                                                                <p class="phrase_meaning trans_fr">{{ $translation['translation'] }}</p>
                                                                            @endif
                                                                        @endforeach
                                                                        <p class="hidden phrase_meaning trans_none">{{t('-')}}</p>
                                                                    @else
                                                                        <p class="phrase_meaning trans_none">{{t('-')}}</p>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            @endif
                                                        @endforeach
                                                    @endforeach
                                                @endif
                                            @endforeach
                                            <tr class="tr-context tr-context-none hidden">
                                                <td colspan="6">
                                                    <p class="record-message">{{t('No Phrases for this Context!')}}</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <script type="text/javascript">
        function onUpload() {
            $("#meaning-area").val('');
            var file = document.getElementById("upload_file").files[0];
            var extension = file['name'].split('.').pop();
            if(extension == 'txt'){
                if(!$('#error_upload').hasClass('hidden')){
                    $('#error_upload').addClass('hidden');
                }
                var textArea = document.getElementById("meaning-area");
                var reader = new FileReader();
                reader.onload = function (e) {
                    textArea.value = e.target.result.substring(0,2500);
                };
                reader.readAsText(file);
                reader.onloadend = function (e) {
                    if($("#meaning-area").val().length > 2500){
                        $('#count').html('Characters: 2500 / 2500');
                    }else{
                        $('#count').html('Characters: '+$("#meaning-area").val().length+' / 2500');
                    }
                };
            }else{
                $('#error_upload').removeClass('hidden');
                $('#error_upload').html('Only .txt type files can be uploaded!');
                $('#count').html('Characters: '+$("#meaning-area").val().length+' / 2500');
            }
        }

        function contextChange(elem) {
            console.log(elem.value);
            if($('.tr-context').hasClass('tr-context-'+elem.value)) {
                $('.tr-context-' + elem.value).siblings('.tr-context').addClass('hidden');
                $('.tr-context-' + elem.value).removeClass('hidden');
            }else{
                $('.tr-context-none').siblings('.tr-context').addClass('hidden');
                $('.tr-context-none').removeClass('hidden');
            }
            $('.exportBlock_'+elem.value).siblings('.exportBlock').addClass('hidden');
            $('.exportBlock_'+elem.value).removeClass('hidden');
        }

        /**
         *
         * @param elem
         */
        function languageChange(elem) {
            if($('.phrase_meaning').hasClass('trans_'+elem.value)){
                $('.phrase_meaning.trans_'+elem.value).removeClass('hidden');
                $('.phrase_meaning.trans_'+elem.value).siblings().addClass('hidden');
            }else{
                $('.phrase_meaning.trans_none').removeClass('hidden');
                $('.phrase_meaning.trans_none').siblings().addClass('hidden');
            }
            if($('.phrase').hasClass('trans_'+elem.value)){
                $('.phrase.trans_'+elem.value).removeClass('hidden');
                $('.phrase.trans_'+elem.value).siblings().addClass('hidden');
            }else{
                $('.phrase.trans_none').removeClass('hidden');
                $('.phrase.trans_none').siblings().addClass('hidden');
            }
        }

        function openRelatedPhrase(e, related_phrase_id) {
            e.preventDefault();
            $('.nav.nav-pills a[href="#tab2"]').tab('show');
            $('html, body').animate({
                scrollTop: $("#related_phrase_td_"+related_phrase_id).offset().top
            }, 2000);
        }
    </script>
    {!! HTML::script('assets/js/login.js') !!}
@endsection