@extends('layouts.secured_header')
@section('title')
    {!! t('Redeem Points') !!}
@stop
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="container-fluid contributorMain redeemBg">
                @include('layouts.flc_header')
                @include('layouts.toaster')
                <div class="row justify-content-md-center mt-4">
                    <div class="col-md-4">
                        <div class="redeem-container">
                            <div class="wrapper">
                                <h1>Redeem points</h1>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th></th>
                                            <th class="text-center">Points</th>
                                            <th class="text-right">Earnings</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $sum=0;?>
                                        @if($points)
                                            @foreach($points as $key=>$point)
                                                <?php $sum=$sum+$point;?>
                                                <tr>
                                                    <td><span class="text-uppercase">{!! Config::get('constant.contributorNames.'.$key) !!}</span></td>
                                                    <td class="text-center">{!! $point !!}</td>
                                                    <td class="text-right">$</td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td>Total</td>
                                                <td class="text-center"></td>
                                                <td class="text-right">${!! $sum !!}</td>
                                            </tr>
                                        @endif

                                        </tbody>
                                    </table>

                                    <div class="text-center mt-4">
                                        <button class="orangeBtn waves-light" data-toggle="modal" data-target="#exampleModal">Redeem</button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{!! t('Redeem Points') !!}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="orangeBtn waves-light align-center grey" disabled>{!! t('Send Request') !!}</button>
                </div>
            </div>
        </div>
    </div>
    {!! HTML::script('assets/js/user/purchase_coins.js') !!}
@endsection
