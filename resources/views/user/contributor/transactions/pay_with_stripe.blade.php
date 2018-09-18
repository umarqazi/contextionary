@extends('layouts.secured_header')
@section('title')
    {!! t('Contributor Select Role') !!}
@stop
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="container-fluid contributorMain purchasCoinBg">
                @include('layouts.flc_header')
                <form class='form-horizontal' method='POST' id='form-submission' role='form' action='{!! lang_route("addmoney.stripe")!!}' >
                    {{ csrf_field() }}
                    @include('stripe_form')
                    <div class='form-row1'>
                        {!! Form::hidden('user_id', $id, []) !!}
                        {!! Form::hidden('price', $coin['price'], []) !!}
                        {!! Form::hidden('package_id', $coin['id'], []) !!}
                        {!! Form::hidden('type', 'purchase_coins', []) !!}
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="orangeBtn waves-effect waves-light">{!! t('Pay with Stripe') !!}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {!! HTML::script('assets/js/user/purchase_coins.js') !!}
@endsection
