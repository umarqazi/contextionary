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
            <a href="{{ url()->previous() }}" class="orangeBtn">Back</a>
        </div>
        <div class="col-md-12 mt-3">
            @if($type == 'context_forwarded')
                <h2>PHRASE: “<span class="sentence_case">{{ucwords($phrase)}}</span>”</h2>
            @endif
            <div class="row">
                <div class="col-md-12">
                    @if(! $meaning->isEmpty())
                        <p class="text-white">{{$meaning[0]->meaning}}</p>
                    @else
                        <p class="text-white">{{t('-')}}</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 mt-5">
            <h2>RELATED PHRASE</h2>
            <div class="phrase-body">
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
                        @endif
                    @endif
                </div>
            </div>
        </div>
        @if( count($shared_words_arry) > 0)
            @foreach($shared_words_arry as $shared_words_key => $shared_words)
                @if($type == 'context_forwarded')
                    @if( !$shared_words->isEmpty())
                    <div class="col-lg-12 col-md-12 mt-5">
                        <h2>PHRASE WITH “{{$phrase_words[$shared_words_key]}}”</h2>
                        <div class="phrase-body related-phrase-body">
                            <div class="row">
                                @foreach($shared_words as $shared_word)
                                    @if( $shared_word->shared_word == $phrase_words[$shared_words_key])
                                        <div class="col-lg-3 col-md-3">
                                            <p class="text-white"><a href="{!! lang_url('learning-center/explore-context-phrase', ['phrase'=>$shared_word->sibling_id ]) !!}">{{ucfirst($shared_word->sibling_name)}}</a></p>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                @elseif($type == 'phrase_forwarded')
                    @if( !$shared_words->isEmpty())
                    <div class="col-lg-12 col-md-12 mt-5">
                        <h2>PHRASE WITH “{{$phrase_words[$shared_words_key]}}”</h2>
                        <div class="phrase-body related-phrase-body">
                            <div class="row">
                                @foreach($shared_words as $shared_word)
                                    @if( $shared_word->shared_word == $phrase_words[$shared_words_key])
                                        <div class="col-lg-3 col-md-3">
                                            <p class="text-white"><a href="{!! lang_url('learning-center/explore-context-phrase', ['phrase'=>$shared_word->sibling_id ]) !!}">{{ucfirst($shared_word->sibling_name)}}</a></p>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                @endif
            @endforeach
        @endif
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
