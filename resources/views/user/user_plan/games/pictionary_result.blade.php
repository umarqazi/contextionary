@extends('layouts.secured_header')
@section('title')
    {!! t('Pictionary') !!}
@stop
@section('content')
    <div class="container-fluid contributorMain pictionaryQuizLanding resultPage">
        <div class="learningModule">
            <div class="wrapper">
                <div class="companyName">Pictionary Results</div>
                <p>Your Score: <span>{{$game->score}}</span></p>
                <p>Your Highest Score: <span>{{$high_score}}</span></p>
                <div class="actions-btn mt-5">
                    <a href="{!! lang_route('start-pictionary') !!}" class="btn orangeBtn waves-effect waves-light">
                        <img src="{!! asset('storage/images/replay-symbol.png') !!}">
                    </a>
                </div>
            </div>
        </div>
    </div>
<script>
    // setTimeout(function(){
    //     window.location.replace("http://localhost:8000/en/pictionary");
    // }, 2000);
</script>
@endsection