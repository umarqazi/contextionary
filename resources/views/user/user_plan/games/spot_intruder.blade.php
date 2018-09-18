@extends('layouts.secured_header')
@section('title')
    {!! t('Spot The Intruder') !!}
@stop
@section('content')
<div class="container-fluid contributorMain funfact pictionaryQuiz">
    @include('layouts.flc_header')
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="score">
                <a href="{!! lang_route('reset-spot-the-intruder') !!}" class="btn orangeBtn waves-effect waves-light">
                    <img src="{!! asset('storage/images/replay-symbol.png') !!}">
                </a>
                <strong>Score:</strong><span id="score-value">{{$game->score}}</span>/20
                <strong>Question:</strong><span>{{$game->question_count}}</span>/20
            </div>
            <div class="gameTitle">
                I AM THE LEAST RELATED TO {{$question->question}}
            </div>
        </div>
        <div class="col-sm-6">
            <button class="gameOption" data-option="option1">
                {{$question->option1}}
            </button>
        </div>
        <div class="col-sm-6">
            <button class="gameOption" data-option="option2">
                {{$question->option2}}
            </button>
        </div>
        <div class="col-sm-6">
            <button class="gameOption"  data-option="option3">
                {{$question->option3}}
            </button>
        </div>
        <div class="col-sm-6">
            <button class="gameOption" data-option="option4">
                {{$question->option4}}
            </button>
        </div>
        <div class="col-sm-12 correct-div hidden">
            <div class="row">
                <div class="col-sm-3 correct-div-image">
                    <img src="{!! asset('storage/images/check.png') !!}">
                </div>
                <div class="col-sm-6 correct-div-text">
                    <h2>Correct Answer!</h2>
                </div>
                <div class="col-sm-3 correct-div-text">
                    <a href="{!! lang_route('spot-the-intruder') !!}"><span>Continue</span></a>
                </div>
            </div>
        </div>
        <div class="col-sm-12 wrong-div hidden">
            <div class="row">
                <div class="col-sm-3 wrong-div-image">
                    <img src="{!! asset('storage/images/cancel.png') !!}">
                </div>
                <div class="col-sm-6 wrong-div-text">
                    <h2>Wrong Answer!</h2>
                    <p>Correct Answer was <strong id="answer"></strong></p>
                </div>
                <div class="col-sm-3 wrong-div-text">
                    <a href="{!! lang_route('spot-the-intruder') !!}"><span>Continue</span></a>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('button.gameOption').click(function() {
        var opt= $(this).attr('data-option');
        $.ajax({
            type: "POST",
            url: '/en/verify-spot-the-intruder',
            data: { game_id:{{$game->id}}, ques_id:{{$question->id}}, option: opt, _token: '{{csrf_token()}}'}
        }).done(function( res ) {
            $('button.gameOption').remove();
            if( res.status){
                $('.correct-div').removeClass('hidden');
                $('#score-value').html(parseInt($('#score-value').html())+1);
            }else{
                $('.wrong-div').removeClass('hidden');
                $('#answer').html(res.body);
            }
        })

    });
</script>
@endsection