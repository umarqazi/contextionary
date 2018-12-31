@extends('layouts.secured_header')
@section('title')
    {!! t('Details of Phrases') !!}
@stop
@section('content')
<div class="container-fluid contributorMain userExploreWord userExploreContext detailExplore" style="background: url({!! asset('storage/'.$illustration) !!}); background-size:cover; position: center center;">
    <div class="wrapperMask"></div>
    @include('layouts.flc_header')
    <div class="row mt-4">
        <div class="col-sm-6">
            @if($type == 'context_forwarded' || $type == 'context_phrase_forwarded')
                <div class="exploreTitle">{{ucwords($context)}}</div>
            @endif
        </div>
        <div class="col-sm-6 text-right">
            @if($type == 'context_forwarded')
                <a href="{{ lang_url('learning-center/explore-context').'/'.Request::segment(4) }}" class="orangeBtn">Back</a>
            @elseif($type == 'context_phrase_forwarded')
                <a href="{{ lang_url('learning-center/explore-context-phrase').'/'.Request::segment(5) }}" class="orangeBtn">Back</a>
            @elseif($type == 'phrase_forwarded')
                <a href="{{ lang_url('learning-center/explore-word') }}" class="orangeBtn">Back</a>
            @endif
        </div>
        <div class="col-md-12 mt-3">
            @if($type == 'context_forwarded' || $type == 'context_phrase_forwarded')
                <h2>{{t('PHRASE: ')}}“<span class="sentence_case">{{ucwords($phrase)}}</span>”</h2>
            @elseif($type == 'phrase_forwarded')
                <h2>{{t('PHRASE: ')}}“<span class="sentence_case">{{ucwords($selected_phrase_text)}}</span>”</h2>
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
            <h2>{{t('RELATED PHRASES')}}</h2>
            <div class="phrase-body">
                <div class="row">
                    @if($type == 'context_forwarded')
                        @if( ! $related_phrases->isEmpty())
                            @foreach($related_phrases as $related_phrase)
                                @if( $related_phrase->relatedPhrases != null)
                                    <div class="col-lg-3 col-md-3">
                                        <p class="text-white"><a href="{!! lang_url('learning-center/explore-context', ['context'=> $context_id, 'phrase'=>$related_phrase->relatedPhrases->phrase_id ]) !!}">{{ucwords($related_phrase->relatedPhrases->phrase_text)}}</a></p>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    @elseif($type == 'context_phrase_forwarded')
                        @if( ! $related_phrases->isEmpty())
                            @foreach($related_phrases as $related_phrase)
                                @if( $related_phrase->relatedPhrases != null)
                                    <div class="col-lg-3 col-md-3">
                                        <p class="text-white"><a href="{!! lang_url('learning-center/explore-context', ['context'=> $context_id, 'phrase'=>$related_phrase->relatedPhrases->phrase_id ]) !!}">{{ucwords($related_phrase->relatedPhrases->phrase_text)}}</a></p>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    @elseif($type == 'phrase_forwarded')
                        @if( ! $related_phrases->isEmpty())
                            @foreach($related_phrases as $related_phrase)
                                @if( $related_phrase->relatedPhrases != null)
                                    <div class="col-lg-3 col-md-3">
                                        <p class="text-white"><a href="{!! lang_url('learning-center/explore-context-phrase', [ 'phrase'=>$related_phrase->relatedPhrases->phrase_id ]) !!}">{{ucwords($related_phrase->relatedPhrases->phrase_text)}}</a></p>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    @endif
                </div>
            </div>
        </div>
        <div class="mb-4">
        @if( count($shared_words_arry) > 0)
            @foreach($shared_words_arry as $shared_words_key => $shared_words)
                @if($type == 'context_forwarded')
                    @if( !$shared_words->isEmpty())
                    <div class="col-lg-12 col-md-12 mt-5">
                        <h2>{{t('PHRASES SHARING THE WORD')}} “{{ucwords($phrase_words[$shared_words_key])}}”</h2>
                        <div class="phrase-body related-phrase-body">
                            <div class="row">
                                @foreach($shared_words as $shared_word)
                                    @if( $shared_word->shared_word == $phrase_words[$shared_words_key])
                                        <div class="col-lg-3 col-md-3">
                                            <p class="text-white"><a href="{!! lang_url('learning-center/explore-context-phrase', ['phrase'=>$shared_word->sibling_id ]) !!}">{{ucwords($shared_word->sibling_name)}}</a></p>
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
                        <h2>{{t('PHRASES SHARING THE WORD')}} “{{ucwords($phrase_words[$shared_words_key])}}”</h2>
                        <div class="phrase-body related-phrase-body">
                            <div class="row">
                                @foreach($shared_words as $shared_word)
                                    @if( $shared_word->shared_word == $phrase_words[$shared_words_key])
                                        <div class="col-lg-3 col-md-3">
                                            <p class="text-white"><a href="{!! lang_url('learning-center/explore-context-phrase', ['phrase'=>$shared_word->sibling_id ]) !!}">{{ucwords($shared_word->sibling_name)}}</a></p>
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
