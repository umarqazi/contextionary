@extends('layouts.app')
@section('title')
{!! t('Login') !!}
@stop
@section('content')

<div class="loginBlock wow fadeIn" data-wow-delay="0.6s">
  <h2>{!! t('Log in')!!}</h2>
  {!! Form::open(['url'=>lang_route('login'), 'method'=>'post'])!!}
  <div class="customForm-group">
    {!! Form::text('email', null,['class'=>'customInput', 'placeholder'=>t('Email')]) !!}
    <span class="focus-border"></span>
    <i class="fa fa-user"></i>
    @if ($errors->has('email'))
    <span class="help-block">
      <strong>{{ t($errors->first('email')) }}</strong>
    </span>
    @endif
  </div>

  <div class="customForm-group">
    {!! Form::password('password', ['class'=>'customInput', 'id'=>"password-field", 'placeholder'=>t('Password')]) !!}
    <span class="focus-border"></span>
    <i toggle="#password-field" class="fa fa-eye toggle-password" style="cursor:pointer"></i>
    @if ($errors->has('password'))
    <span class="help-block">
      <strong>{{ t($errors->first('password')) }}</strong>
    </span>
    @endif
  </div>
  <div class="checkbox">
    <label>
      <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> {!!t('Remember Me')!!}
    </label>
  </div>
  <div class="text-right"><a href="{{ lang_route('password.request') }}">{!! t('Forgot Password')!!} ?</a></div>
  <div class="mt-4">
    <button type="submit" class="orangeBtn waves-light">{!! t('Log in') !!}</button>
    <div class="font14 mt-4">
      <span class="whiteText">{!! t("Don't Have an account?") !!} </span><a href="{{ lang_route('register') }}"> {!! t('Sign Up') !!}</a>
    </div>
  </div>
  {!! Form::close() !!}
</div>
{!! HTML::script('assets/js/login.js') !!}
@endsection
