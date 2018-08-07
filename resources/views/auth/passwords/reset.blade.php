@extends('layouts.app')
@section('title')
{!! t('Reset Password') !!}
@stop
@section('content')
<div class="loginBlock wow fadeIn" data-wow-delay="0.6s">
  <h2>Reset Password</h2>
  @if (session('status'))
      <div class="alert alert-success">
          {{ session('status') }}
      </div>
  @endif
  {!! Form::open(['url'=>lang_route('password.request'), 'method'=>'post'])!!}
  <div class="customForm-group form-group{{ $errors->has('email') ? ' has-error' : '' }}">
    <input type="hidden" name="token" value="{{ $token }}">
    {!! Form::text('email',$email or old('email'),['id'=>'email','class'=>'customInput', 'placeholder'=>'Email']) !!}
    <span class="focus-border"></span>
    <i class="fa fa-user"></i>
    @if ($errors->has('email'))
        <span class="help-block">
            <strong>{{ $errors->first('email') }}</strong>
        </span>
    @endif
  </div>
  <div class="customForm-group">
    {!! Form::password('password',['id'=>'password','class'=>'customInput', 'placeholder'=>'Password']) !!}
    <span class="focus-border"></span>
    <i class="fa fa-lock"></i>
  </div>
  <div class="customForm-group form-group{{ $errors->has('password') ? ' has-error' : '' }}">
    {!! Form::password('password_confirmation',['id'=>'password-confirm','class'=>'customInput', 'placeholder'=>'Confirm Password']) !!}
    <span class="focus-border"></span>
    <i class="fa fa-lock"></i>
    @if ($errors->has('password'))
        <span class="help-block">
            <strong>{{ $errors->first('password') }}</strong>
        </span>
    @endif
  </div>
  <div class="mt-4">
    <button type="submit" class="orangeBtn waves-light">Reset Password</button>
  </div>
  {!! Form::close() !!}
</div>
@endsection
