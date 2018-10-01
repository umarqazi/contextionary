@extends('layouts.secured_header')
@section('title')
    {!! t('Tutorials') !!}
@stop
@section('content')
<div class="container-fluid contributorMain userTutorial">
    <div class="wrapperMask"></div>
    <div class="row">
        <div class="col-md-12">
            <div class="tabsContainer">
                <ul class="customTabs tabsView">
                    <li><a href="#">Context Finder</a></li>
                    <li><a href="#">Text history</a></li>
                    <li class="active"><a href="#">Tutorial</a></li>
                </ul>
                <div class="searchHolder light">
                    <i class="fa fa-search"></i>
                    <input type="search" class="fld" placeholder="Search">
                </div>
            </div>
        </div>

    </div>

    <div class="row mt-4">
        <div class="col-md-12 tut-div">
            @if($tutorial != null)
                {!! html_entity_decode($tutorial) !!}
            @else
                <div class="col-md-12">
                    <div class="text-center">
                        <strong class="record-message">{!! t('No Tutorials available') !!}</strong>
                    </div>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection