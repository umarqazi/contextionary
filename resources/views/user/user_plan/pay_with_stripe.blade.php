@extends('layouts.signup_view')
@section('title')
{!! t('Pay with Strip') !!}
@stop
@section('content')
<div class="col-md-12 text-center">
  <div class="blockStyle wow fadeIn" data-wow-delay="0.6s">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <form class='form-horizontal' method='POST' id='payment-form' role='form' action='{!! lang_route("addmoney.stripe")!!}' >
          {{ csrf_field() }}
          @include('layouts.toaster')
          <div class="planBlock">
            <h1 class="selectPlanTitle"><span>$</span> {!! Config::get('constant.plan_prices.'.$plan) !!} / Month</h1>
            <p><strong>{!! Config::get('constant.packages.'.$plan) !!}</strong><p>
          </div>
          <div class="card-container">
            <div class='form-row1'>
              <div class='col-md-12 form-group required'>
                <label class='control-label'>Card Number</label>
                {!! Form::text('card_no', '', ['class'=>'customInput card-number', 'size'=>'20', 'placeholder'=>'Card Number'])!!}
              </div>
            </div>
            <div class='form-row1'>
              <div class='col-md-4 form-group cvc required'>
                <label class='control-label'>CVV</label>
                {!! Form::text('cvvNumber', '', ['class'=>'customInput card-cvc', 'size'=>'4', 'placeholder'=>'ex. 311'])!!}
              </div>
              <div class='col-md-4 form-group expiration required'>
                <label class='control-label'>Expiration</label>
                {!! Form::text('ccExpiryMonth', '', ['class'=>'customInput card-expiry-month', 'size'=>'2', 'placeholder'=>'MM'])!!}
              </div>
              <div class='col-md-4 form-group expiration required'>
                <label class='control-label'>&nbsp;</label>
                {!! Form::text('ccExpiryYear', '', ['class'=>'customInput card-expiry-year', 'size'=>'4', 'placeholder'=>'YYYY'])!!}
              </div>
            </div>
          </div>
          <div class='form-row1'>
            {!! Form::hidden('price', Config::get('constant.plan_prices.'.$plan), []) !!}
            {!! Form::hidden('user_id', $id, []) !!}
            {!! Form::hidden('package_id', $plan, []) !!}
          </div>
          <div class="mt-4">
            <a href="{!! lang_route('userPlan', ['id'=>$id, 'token'=>$token])!!}" class="back-button orangeBtn waves-effect waves-light">Back</a>
            <button type="submit" class="orangeBtn waves-effect waves-light">Pay with Stripe</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
