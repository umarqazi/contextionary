@extends('layouts.secured_header')
@section('title')
    {!! t('Hangman') !!}
@stop
@section('content')
<div class="container-fluid contributorMain funfact hangmanQuizLanding">
</div>
<script>
    setTimeout(function(){
        window.location.replace("{{lang_route('hangman')}}");
    }, 2000);
</script>
@endsection