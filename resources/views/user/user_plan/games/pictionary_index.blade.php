@extends('layouts.secured_header')
@section('title')
    {!! t('Pictionary') !!}
@stop
@section('content')
<div class="container-fluid contributorMain funfact pictionaryQuizLanding">
</div>
<script>
    setTimeout(function(){
        window.location.replace("{{lang_route('pictionary')}}");
    }, 2000);
</script>
@endsection