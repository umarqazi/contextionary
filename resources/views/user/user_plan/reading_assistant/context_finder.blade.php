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
                {!! Form::textarea('context', null, ['id'=>'meaning-area','class'=>'enter-phrase', 'placeholder'=>'Enter phrase meaning']) !!}
                <p class="text-right white-text"><span id="count-span">{!! t('Characters:') !!} <span id="count">{!! strlen(Input::old('meaning')) !!}/2500</span></span></p>
            </div>

            <div class="col-md-12 mt-4 text-center actionsBtn">
                <button class="orangeBtn ml-3 waves-light">Submit</button>
                <button class="orangeBtn ml-3 waves-light" type="reset" value="Reset" >Reset</button>
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
                    <div class="col-md-6 text-right">
                        <button type="button" class="orangeBtn">Export report</button>
                    </div>

                </div>

                <div class="col-md-12">
                    <div class="exportBlock">
                        {!! $string !!}
                    </div>

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
                                        <tr class="tr-head">
                                            <td>{{t('Jargon')}}</td>
                                            <td>{{t('Meaning')}}</td>
                                            <td>{{t('Illustration')}}</td>
                                            <td>{{t('Related items')}}</td>
                                            <td>{{t('Translation')}}</td>
                                            <td class="text-right lastChild">
                                                <select class="languageBar" onchange="languageChange(this)">
                                                    <option value="en">{{t('English')}}</option>
                                                    <option value="sp">{{t('Spanish')}}</option>
                                                    <option value="hi">{{t('Hindi')}}</option>
                                                    <option value="fr">{{t('French')}}</option>
                                                </select>
                                            </td>
                                        </tr>
                                        @foreach( $context_list as $context_key => $context)
                                            @foreach( $context['phrases'] as $phrase)
                                                @if($context_key != 0)
                                                    <tr class="tr-context tr-context-{{$context['context_id']}} hidden">
                                                @else
                                                    <tr class="tr-context tr-context-{{$context['context_id']}}">
                                                @endif
                                                <td>{{$phrase['phrase']}}</td>
                                                <td>{{$phrase['meaning']}}</td>
                                                <td>
                                                    @if($phrase['illustration'] != '')
                                                        <img src="{!! asset('storage/'.$phrase['illustration']) !!}" class="tableImg">
                                                    @else
                                                        <p>{{t('No Illustration in Records')}}</p>
                                                    @endif
                                                </td>
                                                <td>
                                                    @foreach($phrase['related_phrase'] as $related_phrase)
                                                        <a href="#">{{$related_phrase->relatedPhrases->phrase_text}}</a>{{ $loop->last ? '' : ', ' }}
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @if(count($phrase['translation']) > 0)
                                                        @foreach($phrase['translation'] as $translation)
                                                            @if($translation['language'] == 'English')
                                                                <p class="phrase trans_en">{{ $translation['phrase_translation'] }}</p>
                                                            @elseif($translation['language'] == 'Spanish')
                                                                <p class="hidden phrase trans_sp">{{ $translation['phrase_translation'] }}</p>
                                                            @elseif($translation['language'] == 'Hindi')
                                                                <p class="hidden phrase trans_hi">{{ $translation['phrase_translation'] }}</p>
                                                            @elseif($translation['language'] == 'French')
                                                                <p class="hidden phrase trans_fr">{{ $translation['phrase_translation'] }}</p>
                                                            @endif
                                                        @endforeach
                                                        <p class="hidden phrase trans_none">{{t('No Translation in Records')}}</p>
                                                    @else
                                                        <p class="phrase trans_none">{{t('No Translation in Records')}}</p>
                                                    @endif
                                                </td>
                                                <td class="lastChild">
                                                    @if(count($phrase['translation']) > 0)
                                                        @foreach($phrase['translation'] as $translation)
                                                            @if($translation['language'] == 'English')
                                                                <p class="phrase_meaning trans_en">{{ $translation['translation'] }}</p>
                                                            @elseif($translation['language'] == 'Spanish')
                                                                <p class="hidden phrase_meaning trans_sp">{{ $translation['translation'] }}</p>
                                                            @elseif($translation['language'] == 'Hindi')
                                                                <p class="hidden phrase_meaning trans_hi">{{ $translation['translation'] }}</p>
                                                            @elseif($translation['language'] == 'French')
                                                                <p class="hidden phrase_meaning trans_fr">{{ $translation['translation'] }}</p>
                                                            @endif
                                                        @endforeach
                                                            <p class="hidden phrase_meaning trans_none">{{t('No Translation in Records')}}</p>
                                                    @else
                                                        <p class="phrase_meaning trans_none">{{t('No Translation in Records')}}</p>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div id="tab2" class="tab-pane fade">
                                <div class="table-responsive">
                                    <table class="table contextTable">
                                        <tbody>
                                        <tr>
                                            <td>{{t('Jargon')}}</td>
                                            <td>{{t('Meaning')}}</td>
                                            <td>{{t('Illustration')}}</td>
                                            <td>{{t('Related items')}} </td>
                                            <td>{{t('Translation')}}</td>
                                            <td class="text-right lastChild">
                                                <select class="languageBar">
                                                    <option>French</option>
                                                    <option>French</option>
                                                    <option>French</option>
                                                    <option>French</option>
                                                    <option>French</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Wellhead assembly</td>
                                            <td>Component at the surface of an <a href="#">oil gas</a> as well that provides the interface</td>
                                            <td><img src="images/context-finder-img.jpg" class="tableImg"></td>
                                            <td><a href="#">Blowout preventer, Christmas tre, Pipe ram, shear ram, Blind shear ram</a></td>
                                            <td>Assemblage de tete de puits</td>
                                            <td class="lastChild">Element a <a href="#">la surface</a> d’un puits de petrole ou de gas qui assure <a href="#">I’interface et la.</a></td>
                                        </tr>
                                        <tr>
                                            <td>Wellhead assembly</td>
                                            <td>Component at the surface of an <a href="#">oil gas</a> as well that provides the interface</td>
                                            <td><img src="images/context-finder-img.jpg" class="tableImg"></td>
                                            <td><a href="#">Blowout preventer, Christmas tre, Pipe ram, shear ram, Blind shear ram</a></td>
                                            <td>Assemblage de tete de puits</td>
                                            <td class="lastChild">Element a <a href="#">la surface</a> d’un puits de petrole ou de gas qui assure <a href="#">I’interface et la.</a></td>
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
                        $('#count').html('2500/2500');
                    }else{
                        $('#count').html($("#meaning-area").val().length+'/2500');
                    }
                };
            }else{
                $('#error_upload').removeClass('hidden');
                $('#error_upload').html('Only .txt type files can be uploaded!');
                $('#count').html($("#meaning-area").val().length+'/2500');
            }
        }

        function contextChange(elem) {
                console.log(elem.value);
                $('.tr-context-'+elem.value).siblings('.tr-context').addClass('hidden');
                $('.tr-context-'+elem.value).removeClass('hidden');
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
    </script>
    {!! HTML::script('assets/js/login.js') !!}
@endsection