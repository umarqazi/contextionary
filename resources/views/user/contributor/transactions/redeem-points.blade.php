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
                                            <th class="text-right">{!! t('Earnings') !!}</th>
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
                {!! Form::open(['id'=>'redeem_form', 'url'=>lang_route("saveEarning"), 'enctype'=>'multipart/form-data', 'method'=>'post']) !!}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            {!! Form::select('type', [''=>'Select Type',env('MEANING')=>'Writer', env('ILLUSTRATE')=>'Illustrator', env('TRANSLATE')=>'Translator'], null,['id'=>'role-dropdown','class'=>'form-control role', 'onchange'=>'getCoins()', 'required']) !!}
                            @if ($errors->has('type'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('type') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            {!! Form::number('points', null,['class'=>'form-control', 'id'=>'role_points', 'onkeyup'=>'checkPoint()', 'required']) !!}
                            @if ($errors->has('points'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('points') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-md-12 mt-4">
                            <div class="custom-control custom-checkbox">
                                {!! Form::checkbox('redeem_all', '1', false, ['class'=>'custom-control-input', 'id'=>'checkbox1', 'onChange'=>'redeemAllCheck(this)']); !!} <label class="custom-control-label" for="checkbox1">{!! t('Redeem All Points') !!}</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            {!!  Form::email('Paypal Email', null, ['class'=>'form-control mt-4', 'id'=>'paypal-email', 'placeholder'=> 'Enter Paypal Email', 'required']); !!}
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="orangeBtn waves-light align-center" form="redeem_form" id="request-redee">{!! t('Send Request') !!}</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <script type="text/javascript">
        function redeemAllCheck(elem) {
            if($(elem).is(':checked')){
                $('#role-dropdown').hide();
                $('#role-dropdown').attr('disabled',true);
                $('#role_points').hide();
                $('#role_points').attr('disabled',true);
            }else{
                $('#role-dropdown').show();
                $('#role-dropdown').attr('disabled',false);
                $('#role_points').show();
                $('#role_points').attr('disabled',false);
            }
        }
    </script>
    {!! HTML::script('assets/js/user/purchase_coins.js') !!}
@endsection
