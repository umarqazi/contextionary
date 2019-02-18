@extends('layouts.secured_header')
@section('title')
    {!! t('Messages') !!}
@stop
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="container-fluid contributorMain purchasCoinBg">
                @include('layouts.flc_header')
                @include('layouts.toaster')
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped notification-table">
                                <thead>
                                <tr>
                                    <th>{!! t('Date') !!}</th>
                                    <th>{!! t('Subject') !!}</th>
                                    <th>{!! t('Message') !!}</th>
                                    <th>{!! t('Action') !!}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!$messages->isEmpty())
                                    @foreach($messages as $noti)
                                        @if($noti['data'])
                                            <tr @if(empty($noti['read_at'])) class="highlighted" @endif>
                                                <td>{!! Carbon::parse($noti['created_at'])->format('Y-m-d') !!}</td>
                                                <td>{!! $noti['data']['subject'] !!}</td>
                                                <td>{!! substr($noti['data']['content'],0,100) !!}</td>
                                                <td><a href="{!! lang_route('view-notification', ['id'=>$noti['id']]) !!}" class='btn btn-primary'>View</a> </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="9">
                                            <div class="col-md-12">
                                                <div class="text-center">
                                                    <strong class="record-message">{!! t('No Record') !!}</strong>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                            <div class="col-md-12 mt-4 mb-4 text-center">
                                <div class="customPagination">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! HTML::script('assets/js/user/purchase_coins.js') !!}
@endsection
