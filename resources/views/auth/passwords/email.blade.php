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
  {!! Form::open(['url'=>lang_route('password.email'), 'method'=>'post'])!!}
  <div class="customForm-group">
    {!! Form::text('email', null,['id'=>'email','class'=>'customInput', 'placeholder'=>'Email']) !!}
    <span class="focus-border"></span>
    <i class="fa fa-user"></i>
    @if ($errors->has('email'))
        <span class="help-block">
            <strong>{{ $errors->first('email') }}</strong>
        </span>
    @endif
  </div>
  <div class="mt-4">
    <button type="submit" class="orangeBtn waves-light">Send Password Reset Link</button>
  </div>
  {!! Form::close() !!}
</div>
@endsection
