@extends('layouts.secured_header')
@section('title')
    {!! t('Define a Meaning') !!}
@stop
@section('content')
    <div class="container-fluid">
        @include('layouts.flc_header')
        <div class="row">
            @include('layouts.toaster')
        </div>
        <div class="row">
            
            <div class="col-md-12 mt-4 mb-4 text-center">
                <div class="customPagination">
                    {!! $contextList->links() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
