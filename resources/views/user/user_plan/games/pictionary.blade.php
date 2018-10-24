@extends('layouts.secured_header')
@section('title')
    {!! t('Pictionary') !!}
@stop
@section('content')
<div class="container-fluid contributorMain funfact pictionaryQuiz">
    @include('layouts.flc_header')
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="score">
                <a href="{!! lang_route('reset-pictionary') !!}" class="btn orangeBtn waves-effect waves-light">
                    <img src="{!! asset('storage/images/replay-symbol.png') !!}">
                </a>
                <strong>Score:</strong><span id="score-value">{{$game->score}}</span>/20
                <strong>Question:</strong><span>{{$game->question_count}}</span>/20
            </div>
            <div class="gameTitle">
                {{$pictionary->question}}
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="questionBlock-img">
                <img src="{!! asset('storage/'.$pictionary->pic1) !!}">
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="questionBlock-img">
                <img src="{!! asset('storage/'.$pictionary->pic2) !!}">
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="questionBlock-img">
                <img src="{!! asset('storage/'.$pictionary->pic3) !!}">
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="questionBlock-img">
                <img src="{!! asset('storage/'.$pictionary->pic4) !!}">
            </div>
        </div>
        <div class="col-sm-6 gameOptionDiv">
            <label class="container gameOption" data-option="option1">
                <input type="radio" name="game_ans" value="option1">
                <span class="checkmark"></span>
                {{$pictionary->option1}}
            </label>
        </div>
        <div class="col-sm-6 gameOptionDiv">
            <label class="container gameOption" data-option="option2">
                <input type="radio" name="game_ans" value="option2">
                <span class="checkmark"></span>
                {{$pictionary->option2}}
            </label>
        </div>
        <div class="col-sm-6 gameOptionDiv">
            <label class="container gameOption" data-option="option3">
                <input type="radio" name="game_ans" value="option3">
                <span class="checkmark"></span>
                {{$pictionary->option3}}
            </label>
        </div>
        <div class="col-sm-6 gameOptionDiv">
            <label class="container gameOption" data-option="option4">
                <input type="radio" name="game_ans" value="option4">
                <span class="checkmark"></span>
                {{$pictionary->option4}}
            </label>
        </div>
        <div class="col-sm-12 cont-div">
            <a class="disabled"><span>Submit</span></a>
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
                    <a href="{!! lang_route('continue-pictionary') !!}"><span>Continue</span></a>
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
                    <a href="{!! lang_route('continue-pictionary') !!}"><span>Continue</span></a>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(".gameOptionDiv").click(function () {
        $('.cont-div a').removeClass('disabled');
        $(this).siblings().removeClass('gameOptionActive');
        $(this).addClass('gameOptionActive');
    });
    $('.cont-div a').click(function() {
        if(!$(this).hasClass('disabled')) {
            var opt = $("input[name='game_ans']:checked").val();
            $.ajax({
                type: "POST",
                url: '/en/verify-pictionary',
                async: false,
                data: {game_id:{{$game->id}}, ques_id:{{$pictionary->id}}, option: opt, _token: '{{csrf_token()}}'}
            }).done(function (res) {
                $('.cont-div').remove();
                $(".gameOptionDiv").off();
                if (res.status) {
                    $('.correct-div').removeClass('hidden');
                    $('#score-value').html(parseInt($('#score-value').html()) + 1);
                } else {
                    $('.wrong-div').removeClass('hidden');
                    $('#answer').html(res.body);
                }
            })
        }
    });
</script>
@endsection