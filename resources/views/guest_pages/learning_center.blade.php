@extends('layouts.secured_header')
@section('title')
    {!! t('Learning Center') !!}
@stop
@section('content')
<div class="container-fluid contributorMain userLearningbg">
    @include('layouts.flc_header')
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="learningModule">
                <div class="wrapper">
                    <div class="companyName">Learning module</div>
                    <div class="actions-btn mt-5">
                        <a href="{!! lang_route('explore-context') !!}" class="btn orangeBtn">Explore a context</a>
                        <br>
                        <a href="{!! lang_route('explore-word') !!}" class="btn orangeBtn blue-btn mt-2">Explore a Phrase</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection