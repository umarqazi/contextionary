@extends('layouts.secured_header')
@section('title')
    {!! t('Pictionary') !!}
@stop
@section('content')
<div class="container-fluid contributorMain funfact spotIntruderQuizLanding">
</div>
<script>
    setTimeout(function(){
        window.location.replace("{{lang_route('intruder')}}");
    }, 2000);
</script>
@endsection