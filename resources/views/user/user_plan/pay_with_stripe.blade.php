@extends('layouts.signup_view')
@section('title')
{!! t('Pay with Strip') !!}
@stop
@section('content')
<div class="col-md-12 text-center">
  <div class="blockStyle wow fadeIn" data-wow-delay="0.6s">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <form class='form-horizontal' method='POST' id='form-submission' role='form' action='{!! lang_route("addmoney.stripe")!!}' >
          {{ csrf_field() }}
          @include('layouts.toaster')
          <div class="planBlock">
            <h1 class="selectPlanTitle"><span>$</span> {!! Config::get('constant.plan_prices.'.$plan) !!} / Month</h1>
            <p><strong>{!! Config::get('constant.packages.'.$plan) !!}</strong><p>
          </div>
          @include('stripe_form')
          <div class='form-row1'>
            {!! Form::hidden('price', Config::get('constant.plan_prices.'.$plan), []) !!}
            {!! Form::hidden('user_id', $id, []) !!}
            {!! Form::hidden('package_id', $plan, []) !!}
            {!! Form::hidden('type', 'buy_package', []) !!}
          </div>
          <div class="mt-4">
            <a href="{!! URL::previous()!!}" class="back-button orangeBtn waves-effect waves-light">{!! t('Back') !!}</a>
            <button type="submit" class="orangeBtn waves-effect waves-light">{!! t('Pay with Stripe') !!}</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
