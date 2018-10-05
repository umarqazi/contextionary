@extends('layouts.secured_header')
@section('title')
    {!! t('Purchase Coins') !!}
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
                                    <th>Date</th>
                                    <th>Transaction Type</th>
                                    <th>Role</th>
                                    <th>Coins / Points</th>
                                    <th>Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($transactions)
                                    @foreach($transactions as $transaction)
                                    <tr>
                                        <td>{!! $transaction['created_at'] !!}</td>
                                        <td>{!! Config::get('constant.purchase_type.'.$transaction['purchase_type']) !!}</td>
                                        <td></td>
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
