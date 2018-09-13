@extends('layouts.secured_header')
@section('title')
    {!! t('Pictionary') !!}
@stop
@section('content')
<div class="container-fluid contributorMain funfact spotIntruderQuizLanding">
</div>
<script>
    setTimeout(function(){
        window.location.replace("/en/intruder");
    }, 2000);
</script>
@endsection