@extends('layouts.secured_header')
@section('title')
    {!! t('Pictionary') !!}
@stop
@section('content')
    <div class="container-fluid contributorMain userExploreWord userExploreContext2 resultPage">
        @include('layouts.flc_header')
        <div class="learningModule">
            <div class="wrapper">
                <div class="companyName">Explore A Word</div>
                <div class="actions-btn mt-5">
                    @include('user.user_plan.learning_center.context_search')
                </div>
            </div>
        </div>
    </div>
@endsection