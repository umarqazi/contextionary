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
                                            <th class="text-center">{!! t('Points') !!}</th>
                                            <th class="text-right">{!! t('Value') !!}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $sum=0; $total_points=0;?>
                                        @if($contributions['points'])
                                            @foreach($contributions['points'] as $key=>$point)
                                                <?php $earning=0; ?>
                                                @foreach($pointsPrices as $key2=>$range)
                                                    <?php
                                                    if($point >=1000 && ($range['min_points']==0) && ($point >= $range['max_points'])):
                                                        $earning=$range['price']*$point;
                                                        break;
                                                    elseif(($point >= $range['min_points']) && ($point <= $range['max_points'])):
                                                        $earning=$range['price']*$point;
                                                        break;
                                                    endif;
                                                    ?>
                                                @endforeach
                                                <?php $sum=$sum+$earning; $total_points=$total_points+$point?>
                                                <tr>
                                                    <td><span class="text-uppercase">{!! Config::get('constant.contributorNames.'.$key) !!}</span></td>
                                                    <td class="text-center"><span id="{!! $key !!}">{!! $point !!}</span></td>

                                                    <td class="text-right">${!! $earning !!}</td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td>Total</td>
                                                <td class="text-center">{!! $total_points !!}</td>
                                                <td class="text-right">${!! $sum !!}</td>
                                            </tr>
                                        @endif

                                        </tbody>
                                    </table>
                                    @if($sum)
                                        <div class="text-center mt-4">
                                            <button class="orangeBtn waves-light" data-toggle="modal" data-target="#pointModal">{!! t('Redeem') !!}</button>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="pointModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content .modal-lg">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{!! t('Redeem Points') !!}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                {!! Form::open(['id'=>'form-submission', 'url'=>lang_route("saveEarning"), 'enctype'=>'multipart/form-data', 'method'=>'post']) !!}
                <div class="modal-body">
                    @foreach($contributions['points'] as $key=>$points)
                        <div class="row mt-4">
                            <div class="col-md-3">
                                <p class="text-capitalize"><strong>{!! Config::get('constant.contributorNames.'.$key) !!}</strong></p>
                            </div>
                            <div class="col-md-9">
                                {!! Form::number($key, $points,[($points <= 0)?'readonly':'','class'=>'form-control', 'id'=>'role_points', 'onkeyup'=>'checkPoint()', 'required']) !!}
                                @if ($errors->has($key))
                                    <span class="help-block">
                                    <strong>{{ $errors->first($key) }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    @endforeach


                    <div class="row">
                        <div class="col-md-12">
                            <img src="{!! asset('/assets/images/2000px-PayPal.svg.png') !!}" class="height25 mt-4">
                            {!!  Form::email('Paypal Email', Auth::user()->paypal_email, ['class'=>'form-control mt-2', 'id'=>'paypal-email', 'placeholder'=> 'Enter Paypal Email', 'required']); !!}
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="orangeBtn waves-light align-center">{!! t('Send Request') !!}</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    {!! HTML::script('assets/js/user/purchase_coins.js') !!}
@endsection
