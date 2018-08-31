@extends('layouts.secured_header')
@section('title')
    {!! t('Contributor Select Role') !!}
@stop
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="container-fluid contributorMain purchasCoinBg">
                <div class="row">
                    <div class="col-md-12">
                        <div class="tabsContainer">
                            <ul class="customTabs tabsView">
                                <li class="active"><a href="#">Purchase coins</a></li>
                                <li><a href="#">redeem points</a></li>
                                <li><a href="#">Summary</a></li>
                            </ul>
                            <div class="searchHolder light">
                                <i class="fa fa-search"></i>
                                <input type="search" class="fld" placeholder="Search">
                            </div>
                        </div>
                    </div>
                </div>
                <form class='form-horizontal' method='POST' id='payment-form' role='form' action='{!! lang_route("addmoney.stripe")!!}' >
                    {{ csrf_field() }}
                    @include('stripe_form')
                    <div class='form-row1'>
                        {!! Form::hidden('user_id', $id, []) !!}
                        {!! Form::hidden('price', $coin['price'], []) !!}
                        {!! Form::hidden('package_id', $coin['id'], []) !!}
                        {!! Form::hidden('type', 'purchase_coins', []) !!}
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="orangeBtn waves-effect waves-light">Pay with Stripe</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {!! HTML::script('assets/js/user/purchase_coins.js') !!}
@endsection
