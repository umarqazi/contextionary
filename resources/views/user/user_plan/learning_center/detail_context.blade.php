@extends('layouts.secured_header')
@section('title')
    {!! t('Details of Phrases') !!}
@stop
@section('content')
<div class="container-fluid contributorMain userExploreWord userExploreContext">
    @include('layouts.flc_header')
    <div class="row mt-4">
        <div class="col-sm-6">
            @if($type == 'context_forwarded')
                <div class="exploreTitle">{{ucfirst($context)}}</div>
            @elseif($type == 'phrase_forwarded')
                <div class="exploreTitle">{{ucfirst($selected_phrase_text)}}</div>
            @endif
        </div>
        <div class="col-sm-6 text-right">
            @php
                $url = explode('/', Request::url());
                array_pop($url);
            @endphp
            @if($type == 'context_forwarded')
                {{--<a href="{!! implode('/', $url) !!}" class="orangeBtn">Back</a>--}}
                <a href="{{ url()->previous() }}" class="orangeBtn">Back</a>
            @elseif($type == 'phrase_forwarded')
                {{--<a href="{!! lang_url('learning-center/explore-word') !!}" class="orangeBtn">Back</a>--}}
                <a href="{{ url()->previous() }}" class="orangeBtn">Back</a>
            @endif
        </div>
        <div class="col-md-12 mt-3">
            @if($type == 'context_forwarded')
                <h2>{{$phrase}}</h2>
            @endif
            <div class="row">
                <div class="col-md-12">
                    @if(! $meaning->isEmpty())
                        <p class="text-white"><strong class="mr-2">Phrase Meaning :</strong> “{{$meaning[0]->meaning}}”</p>
                    @else
                        <p class="text-white"><strong class="mr-2">Phrase Meaning :</strong> {{t('No Meaning in Records.')}}</p>
                    @endif
                </div>
            </div>
            @if(! $translations->isEmpty())
                <div class="row mb-1">
                    <div class="col-md-12">
                        <p class="text-white phraseTranslate"><strong class="mr-2">Language :</strong></p>
                        <div class="languageBar mb-1">
                            <span class="active"> Select Language <i class="fa fa-chevron-down"></i></span>
                            <ul class="list">
                                <li><img src="{{asset('assets/images/french-flag.png')}}"> French</li>
                                <li><img src="{{asset('assets/images/spain-flag.png')}}"> Spanish</li>
                                <li><img src="{{asset('assets/images/hindi-flag.png')}}"> Hindi</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row div-translate">
                    @foreach($translations as $translation)
                        @if($translation->language == 'Spanish')
                            <div class="pmt-pt-spanish hidden">
                                <div class="col-md-12">
                                    <p class="text-white phraseTranslate mb-1 pt-spanish">
                                        @if($translation->phrase_translation != '')
                                            <strong class="mr-2">Phrase Translation :</strong> {{$translation->phrase_translation}}
                                        @else
                                            <strong class="mr-2">Phrase Translation :</strong> No Translation Available
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-12">
                                    <p class="text-white phraseTranslate pmt-spanish">
                                        @if($translation->translation != '')
                                            <strong class="mr-2">Phrase Meaning Translation :</strong> {{$translation->translation}}
                                        @else
                                            <strong class="mr-2">Phrase Meaning Translation :</strong> No Translation Available
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @elseif($translation->language == 'French')
                            <div class="pmt-pt-french hidden">
                                <div class="col-md-12">
                                    <p class="text-white phraseTranslate mb-1 pt-french">
                                        @if($translation->phrase_translation != '')
                                            <strong class="mr-2">Phrase Translation :</strong> {{$translation->phrase_translation}}
                                        @else
                                            <strong class="mr-2">Phrase Translation :</strong> No Translation Available
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-12">
                                    <p class="text-white phraseTranslate pmt-french">
                                        @if($translation->translation != '')
                                            <strong class="mr-2">Phrase Meaning Translation :</strong> {{$translation->translation}}
                                        @else
                                            <strong class="mr-2">Phrase Meaning Translation :</strong> No Translation Available
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @elseif($translation->language == 'Hindi')
                            <div class="pmt-pt-hindi hidden">
                                <div class="col-md-12">
                                    <p class="text-white phraseTranslate mb-1 pt-hindi">
                                        @if($translation->phrase_translation != '')
                                            <strong class="mr-2">Phrase Translation :</strong> {{$translation->phrase_translation}}
                                        @else
                                            <strong class="mr-2">Phrase Translation :</strong> No Translation Available
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-12">
                                    <p class="text-white phraseTranslate pmt-hindi">
                                        @if($translation->translation != '')
                                            <strong class="mr-2">Phrase Meaning Translation :</strong> {{$translation->translation}}
                                        @else
                                            <strong class="mr-2">Phrase Meaning Translation :</strong> No Translation Available
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @endif
                    @endforeach
                        <div class="pmt-pt-na hidden">
                            <div class="col-md-12">
                                <p class="text-white phraseTranslate mb-1 pt-na">
                                    <strong class="mr-2">Phrase Translation :</strong> No Translation Available
                                </p>
                            </div>
                            <div class="col-md-12">
                                <p class="text-white phraseTranslate pmt-na">
                                    <strong class="mr-2">Phrase Meaning Translation :</strong> No Translation Available
                                </p>
                            </div>
                        </div>
                </div>
            @else
                <div class="row div-translate">
                    <div class="pmt-pt-na">
                        <div class="col-md-12">
                            <p class="text-white phraseTranslate mb-1 pt-na">
                                <strong class="mr-2">Phrase Translation :</strong> No Translation Available
                            </p>
                        </div>
                        <div class="col-md-12">
                            <p class="text-white phraseTranslate pmt-na">
                                <strong class="mr-2">Phrase Meaning Translation :</strong> No Translation Available
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        @php
            //dd($shared_words_arry);
        @endphp

        @if( count($shared_words_arry) > 0)
            @foreach($shared_words_arry as $shared_words_key => $shared_words)
                <div class="col-lg-12 col-md-12 mt-5">
                    <h2>Shared Words for “{{$phrase_words[$shared_words_key]}}”</h2>
                    <div class="phrase-body">
                        <div class="row">
                            @if($type == 'context_forwarded')
                                @if( !$shared_words->isEmpty())
                                    @foreach($shared_words as $shared_word)
                                        @if( $shared_word->shared_word == $phrase_words[$shared_words_key])
                                            <div class="col-lg-3 col-md-3">
                                                <p class="text-white"><a href="{!! lang_url('learning-center/explore-context-phrase', ['phrase'=>$shared_word->sibling_id ]) !!}">{{ucfirst($shared_word->sibling_name)}}</a></p>
                                            </div>
                                        @endif
                                    @endforeach
                                @else
                                    <div class="col-lg-12 col-md-12">
                                        <p class="text-white">{{t('No Shared Words in  Records')}}</p>
                                    </div>
                                @endif
                            @elseif($type == 'phrase_forwarded')
                                @if( !$shared_words->isEmpty())
                                    @foreach($shared_words as $shared_word)
                                        @if( $shared_word->shared_word == $phrase_words[$shared_words_key])
                                            <div class="col-lg-3 col-md-3">
                                                <p class="text-white"><a href="{!! lang_url('learning-center/explore-context-phrase', ['phrase'=>$shared_word->sibling_id ]) !!}">{{ucfirst($shared_word->sibling_name)}}</a></p>
                                            </div>
                                        @endif
                                    @endforeach
                                @else
                                    <div class="col-lg-12 col-md-12">
                                        <p class="text-white">{{t('No Shared Words in  Records')}}</p>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-lg-12 col-md-12 mt-5">
                <h2>Shared Words</h2>
                <p class="text-white">{{t('No Shared Words in  Records')}}</p>
            </div>
        @endif
        <div class="col-lg-12 col-md-12 mt-5">
            @if($type == 'context_forwarded')
                <h2>Phrase related to {{ucfirst($context)}}</h2>
            @elseif($type == 'phrase_forwarded')
                <h2>Phrase related to {{ucfirst($selected_phrase_text)}}</h2>
            @endif
            <div class="phrase-body related-phrase-body">
                <div class="row">
                    @if($type == 'context_forwarded')
                        @if( ! $related_phrases->isEmpty())
                            @foreach($related_phrases as $related_phrase)
                                @if( $related_phrase->relatedPhrases != null)
                                    <div class="col-lg-3 col-md-3">
                                        <p class="text-white"><a href="{!! lang_url('learning-center/explore-context', ['context'=> $context_id, 'phrase'=>$related_phrase->relatedPhrases->phrase_id ]) !!}">{{ucfirst($related_phrase->relatedPhrases->phrase_text)}}</a></p>
                                    </div>
                                @endif
                            @endforeach
                        @else
                            <div class="col-lg-12 col-md-12">
                                <p class="text-white">{{t('No Related Phrases in Records')}}</p>
                            </div>
                        @endif
                    @elseif($type == 'phrase_forwarded')
                        @if( ! $related_phrases->isEmpty())
                            @foreach($related_phrases as $related_phrase)
                                @if( $related_phrase->relatedPhrases != null)
                                    <div class="col-lg-3 col-md-3">
                                        <p class="text-white"><a href="{!! lang_url('learning-center/explore-context-phrase', [ 'phrase'=>$related_phrase->relatedPhrases->phrase_id ]) !!}">{{ucfirst($related_phrase->relatedPhrases->phrase_text)}}</a></p>
                                    </div>
                                @endif
                            @endforeach
                        @else
                            <div class="col-lg-12 col-md-12">
                                <p class="text-white">{{t('No Related Phrases in Records')}}</p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
    <script type="text/javascript">
        $(".languageBar .list li").click(function () {
            var get_value = $(this).html();
            if($('.pmt-pt-'+get_value.split(" ").pop().toLowerCase()).length){
                $('.pmt-pt-'+get_value.split(" ").pop().toLowerCase()).removeClass('hidden');
                $('.pmt-pt-'+get_value.split(" ").pop().toLowerCase()).siblings().addClass('hidden');
            }else{
                $('.pmt-pt-na').removeClass('hidden');
                $('.pmt-pt-na').siblings().addClass('hidden');
            }
        });
    </script>
@endsection
