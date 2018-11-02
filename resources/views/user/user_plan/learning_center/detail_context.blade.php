@extends('layouts.secured_header')
@section('title')
    {!! t('Explore Word') !!}
@stop
@section('content')
<div class="container-fluid contributorMain userExploreWord userExploreContext">
    @include('layouts.flc_header')
    <div class="row mt-4">
        <div class="col-sm-6">
            <div class="exploreTitle">{{$context}}</div>
        </div>
        <div class="col-sm-6 text-right">
            @php
                $url = explode('/', Request::url());
                array_pop($url);
            @endphp
            <a href="{!! implode('/', $url) !!}" class="orangeBtn">Back</a>
        </div>
        <div class="col-md-12 mt-3">
            <h2>{{$phrase}}</h2>
            @if(! $meaning->isEmpty())
                <div class="row">
                    <div class="col-md-12">
                        <p class="text-white"><strong class="mr-2">Phrase Meaning :</strong> “{{$meaning[0]->meaning}}”</p>
                    </div>
                </div>
            @endif
            @if(! $translations->isEmpty())
            <div class="row">
                <div class="col-md-12">
                    <p class="text-white phraseTranslate"><strong class="mr-2">Language :</strong></p>
                    <div class="languageBar mb-1">
                        <span class="active"><img src="images/english-flag.png"> English <i class="fa fa-chevron-down"></i></span>
                        <ul class="list">
                            <li><img src="images/english-flag.png"> English</li>
                            <li><img src="images/french-flag.png"> French</li>
                            <li><img src="images/spain-flag.png"> Spanish</li>
                            <li><img src="images/hindi-flag.png"> Hindi</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach($translations as $translation)
                    @if($translation->language == 'English')
                        <div class="pmt-pt-english">
                            <div class="col-md-12">
                                <p class="text-white phraseTranslate mb-1 pmt-english"><strong class="mr-2">Phrase Meaning Translation :</strong> {{$translation->translation}}</p>
                            </div>
                            <div class="col-md-12">
                                <p class="text-white phraseTranslate pt-english"><strong class="mr-2">Phrase Translation :</strong> {{$translation->phrase_translation}}</p>
                            </div>
                        </div>
                    @elseif($translation->language == 'Spanish')
                        <div class="pmt-pt-spanish hidden">
                            <div class="col-md-12">
                                <p class="text-white phraseTranslate mb-1 pmt-spanish"><strong class="mr-2">Phrase Meaning Translation :</strong> {{$translation->translation}}</p>
                            </div>
                            <div class="col-md-12">
                                <p class="text-white phraseTranslate pt-spanish"><strong class="mr-2">Phrase Translation :</strong> {{$translation->phrase_translation}}</p>
                            </div>
                        </div>
                    @elseif($translation->language == 'French')
                        <div class="pmt-pt-french hidden">
                            <div class="col-md-12">
                                <p class="text-white phraseTranslate mb-1 pmt-french"><strong class="mr-2">Phrase Meaning Translation :</strong> {{$translation->translation}}</p>
                            </div>
                            <div class="col-md-12">
                                <p class="text-white phraseTranslate pt-french"><strong class="mr-2">Phrase Translation :</strong> {{$translation->phrase_translation}}</p>
                            </div>
                        </div>
                    @elseif($translation->language == 'Hindi')
                        <div class="pmt-pt-hindi hidden">
                            <div class="col-md-12">
                                <p class="text-white phraseTranslate mb-1 pmt-hindi"><strong class="mr-2">Phrase Meaning Translation :</strong> {{$translation->translation}}</p>
                            </div>
                            <div class="col-md-12">
                                <p class="text-white phraseTranslate pt-hindi"><strong class="mr-2">Phrase Translation :</strong> {{$translation->phrase_translation}}</p>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            @endif
        </div>

        <div class="col-lg-4 col-md-6 mt-5">
            <h2>Lexical Sets</h2>
            <div class="phrase-body">
                <p class="text-white">Lorem ipsum dolor sit amet</p>
                <p class="text-white">Lorem ipsum dolor sit amet</p>
                <p class="text-white">Lorem ipsum dolor sit amet</p>
                <p class="text-white">Lorem ipsum dolor sit amet</p>
                <p class="text-white">Lorem ipsum dolor sit amet</p>
                <p class="text-white">Lorem ipsum dolor sit amet</p>
                <p class="text-white">Lorem ipsum dolor sit amet</p>
                <p class="text-white">Lorem ipsum dolor sit amet</p>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mt-5">
            <h2>Lexical Sets</h2>
            <div class="phrase-body">
                <p class="text-white">Lorem ipsum dolor sit amet</p>
                <p class="text-white">Lorem ipsum dolor sit amet</p>
                <p class="text-white">Lorem ipsum dolor sit amet</p>
                <p class="text-white">Lorem ipsum dolor sit amet</p>
                <p class="text-white">Lorem ipsum dolor sit amet</p>
                <p class="text-white">Lorem ipsum dolor sit amet</p>
                <p class="text-white">Lorem ipsum dolor sit amet</p>
                <p class="text-white">Lorem ipsum dolor sit amet</p>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mt-5">
            <h2>Lexical Sets</h2>
            <div class="phrase-body">
                <p class="text-white">Lorem ipsum dolor sit amet</p>
                <p class="text-white">Lorem ipsum dolor sit amet</p>
                <p class="text-white">Lorem ipsum dolor sit amet</p>
                <p class="text-white">Lorem ipsum dolor sit amet</p>
                <p class="text-white">Lorem ipsum dolor sit amet</p>
                <p class="text-white">Lorem ipsum dolor sit amet</p>
                <p class="text-white">Lorem ipsum dolor sit amet</p>
                <p class="text-white">Lorem ipsum dolor sit amet</p>
            </div>
        </div>
    </div>
</div>
    <script type="text/javascript">
        $(".languageBar").click(function () {
            $(".list li").click(function () {
                var get_value = $(this).html();
                $('.pmt-pt-'+get_value.split(" ").pop().toLowerCase()).removeClass('hidden');
                $('.pmt-pt-'+get_value.split(" ").pop().toLowerCase()).siblings().addClass('hidden');
            });
        });
    </script>
@endsection