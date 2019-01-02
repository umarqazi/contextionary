@extends('layouts.secured_header')
@section('title')
    {!! t('Text History') !!}
@stop
@section('content')
    <div class="container-fluid contributorMain summaryBg textFinder">
        <div class="wrapperMask"></div>
        <div class="row">
            <div class="col-md-12">
                @include('layouts.flc_header')
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Description</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($text_histories as $key => $text_history)
                            <tr>
                                <td>{{$key+1}}</td>
                                @if(strlen($text_history->text) > 100)
                                    <td>{{substr($text_history->text, 0, 200).'...'}}</td>
                                @else
                                    <td>{{$text_history->text}}</td>
                                @endif
                                <td>{{\Carbon\Carbon::createFromTimeStamp(strtotime($text_history->created_at))->diffForHumans()}}</td>
                                {{--<td class="text-right"><a href="#" class="viewIcon"><i class="fa fa-eye"></i></a></td>--}}
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        <div class="col-md-12 mt-4 text-center">
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    {{$text_histories->links()}}
                </div>
                <div class="col-md-4"></div>
            </div>
        </div>
    </div>
@endsection