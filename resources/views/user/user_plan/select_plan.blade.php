@extends('layouts.signup_view')
@section('title')
{!! t('Choose a Plan') !!}
@stop
@section('content')

<div class="col-md-10 text-center">
  @include('layouts.toaster')
  <div class="blockStyle wow fadeIn" data-wow-delay="0.6s">
    <h2 class="text-center">{!! t('Select your plan') !!}</h2>
    <div class="row">
      <div class="col-md-6">
        <div class="planBlock">
          <h3 class="orange">{!! t('User Plan') !!}</h3>
          <p>{!! t('User Plan Description') !!}</p>
          <a href="{!! lang_route('userPlan')!!}" class="orangeBtn mt-4 waves-light">{!! t('continue') !!}</a>
        </div>
      </div>
      <div class="col-md-6">
        <div class="planBlock">
          <h3 class="BlueBackground">{!! t('Contributor Plan') !!}</h3>
          <p>{!! t('Contributor Plan Description') !!}</p>
          <a href="{!! lang_route('contributorPlan')!!}" class="orangeBtn mt-4 waves-light">{!! t('continue') !!}</a>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection
