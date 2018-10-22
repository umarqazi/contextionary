@extends('layouts.secured_header')
@section('title')
    {!! t('Summary') !!}
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
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>{!! t('Date') !!}</th>
                                    <th>{!! t('Transaction Type') !!}</th>
                                    <th>{!! t('Role') !!}</th>
                                    <th>{!! t('Coins / Points') !!}</th>
                                    <th>{!! t('Amount') !!}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($transactions)
                                    @foreach($transactions as $transaction)
                                    <tr>
                                        <td>{!! $transaction['created_at'] !!}</td>
                                        <td>{!! Config::get('constant.purchase_type.'.$transaction['purchase_type']) !!}</td>
                                        <td class="text-capitalize">{!! ($transaction['role'])? Config::get('constant.contributorNames.'.$transaction['role']):'N/A' !!}</td>
                                        <td>{!! $transaction['coins'] !!}</td>
                                        <td>${!! $transaction['amount'] !!}</td>
                                    </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                            <div class="col-md-12 mt-4 mb-4 text-center">
                                <div class="customPagination">
                                    {!! $transactions->links() !!}
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
