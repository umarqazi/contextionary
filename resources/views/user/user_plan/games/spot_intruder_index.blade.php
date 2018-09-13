@extends('layouts.secured_header')
@section('title')
    {!! t('Pictionary') !!}
@stop
@section('content')
<div class="container-fluid contributorMain funfact spotIntruderQuizLanding">
</div>
<script>
    setTimeout(function(){
        window.location.replace("http://localhost:8000/en/spot-the-intruder");
    }, 2000);
</script>
@endsection