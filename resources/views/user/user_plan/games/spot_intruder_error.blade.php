@extends('layouts.secured_header')
@section('title')
    {!! t('Spot The Intruder') !!}
@stop
@section('content')
    <div class="container-fluid contributorMain spotIntruderQuizLanding resultPage">
        <div class="learningModule">
            <div class="wrapper">
                <div class="companyName">Spot The Intruder</div>
                <p>{{$message}}</p>
            </div>
        </div>
    </div>
<script>
    // setTimeout(function(){
    //     window.location.replace("http://localhost:8000/en/pictionary");
    // }, 2000);
</script>
@endsection