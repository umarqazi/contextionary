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
                {!! Form::open(['method'=>'POST', 'url'=>lang_route('addCoins'), 'id'=>'form-submission']) !!}
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="row">
                            @if(!$coins->isEmpty())
                                @foreach($coins as $coin)
                                    <div class="col-sm-6 col-md-4">
                                        <label class="click-coins">
                                            <input type="radio" name="coins" value="{!! $coin['id'] !!}" style="display: none;">
                                            <div class="coin-block">
                                                <img src="{!! asset('assets/images/coin-img1.jpg') !!}" class="coin-img">
                                                <div class="mask"></div>
                                                <div class="info">
                                                    <h1>$ {!! $coin['price'] !!}</h1>
                                                    <p>{!! $coin['coins'] !!} {!! t('Coins') !!}</p>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                @endforeach;
                                <div class="col-sm-12 col-md-12 mt-4 mb-3 text-center hide-button" id="show-purchase">
                                    <button type="submit" class="orangeBtn waves-light">{!! t('Purchase') !!}</button>
                                </div>
                            @else
                                <div class="col-md-12">
                                    <div class="text-center">
                                        <strong class="record-message">{!! t('This functionality will be available soon. Thank you for your patience.') !!}</strong>
                                    </div>
                                </div>
                            @endif;
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    {!! HTML::script('assets/js/user/purchase_coins.js') !!}
@endsection
