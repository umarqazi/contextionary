@extends('layouts.secured_header')
@section('title')
    {!! t('Phrase List') !!}
@stop
@section('content')
    <div class="container-fluid">
        @include('layouts.flc_header')
        <div class="row">
            @include('layouts.toaster')
        </div>
        @include('user.contributor.context_list')
    </div>
@endsection
