@extends('layouts.signup_view')
@section('title')
{!! t('Sign Up') !!}
@stop
@section('content')

<div class="col-md-10 text-center">
  <div class="blockStyle wow fadeIn" data-wow-delay="0.6s">
    @include('layouts.toaster')
    <h2>{!! t('SIGN UP') !!}</h2>
    {!! Form::open(['url'=>lang_route("register"),'enctype'=>'multipart/form-data', 'method'=>'post', 'id'=>'form-submission']) !!}
    <div class="row">
      <div class="col-md-6">
        <div class="customForm-group">
          {!! Form::text('first_name', Input::old('first_name'), ['class'=>'customInput', 'placeholder'=>t('First Name')]) !!}
          <span class="focus-border"></span>
          <i class="fa fa-user"></i>
          @if ($errors->has('first_name'))
          <span class="help-block">
            <strong>{{ t($errors->first('first_name')) }}</strong>
          </span>
          @endif
        </div>
      </div>
      <div class="col-md-6">
        <div class="customForm-group">
          {!! Form::text('last_name', Input::old('last_name'), ['class'=>'customInput', 'placeholder'=>'Last Name']) !!}
          <span class="focus-border"></span>
          <i class="fa fa-user"></i>
          @if ($errors->has('last_name'))
          <span class="help-block">
            <strong>{{ t($errors->first('last_name')) }}</strong>
          </span>
          @endif
        </div>
      </div>

      <div class="col-md-6">
        <div class="customForm-group">
          {!! Form::text('pseudonyme', Input::old('pseudonyme'), ['class'=>'customInput', 'placeholder'=>t('Pseudonyme')]) !!}
          <span class="focus-border"></span>
          <i class="fa fa-user"></i>
          @if ($errors->has('pseudonyme'))
          <span class="help-block">
            <strong>{{ t($errors->first('pseudonyme')) }}</strong>
          </span>
          @endif
        </div>
      </div>

      <div class="col-md-6">
        <div class="customForm-group">
          <div class="input-group with-addon-icon-left customDatePicker">
            <input type="text" value="{!! Input::old('date_birth') !!}" name="date_birth" class="form-control custom-fld" id="datepicker-example-1" placeholder="Date of birth">
            <span class="input-group-append"><span class="input-group-text"><i class="fa fa-calendar"></i></span></span>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="customForm-group">
          {!! Form::select('gender',['Male'=>'Male', 'Female'=>'Female'] ,null, ['class'=>'customSelect w-100', 'placeholder'=>'Gender']) !!}
          @if ($errors->has('gender'))
          <span class="help-block">
            <strong>{{ t($errors->first('gender')) }}</strong>
          </span>
          @endif
        </div>
      </div>

      <div class="col-md-6">
        <div class="customForm-group">
          {!! Form::text('email', Input::old('email'), ['class'=>'customInput', 'placeholder'=>'Email']) !!}
          <span class="focus-border"></span>
          <i class="fa fa-envelope"></i>
          @if ($errors->has('email'))
          <span class="help-block">
            <strong>{{ t($errors->first('email')) }}</strong>
          </span>
          @endif
        </div>
      </div>

      <div class="col-md-6">
        <div class="customForm-group">
          {!! Form::text('phone_number', Input::old('phone_number'), ['class'=>'customInput', 'placeholder'=>'Phone']) !!}
          <span class="focus-border"></span>
          <i class="fa fa-phone"></i>
          @if ($errors->has('phone_number'))
          <span class="help-block">
            <strong>{{ t($errors->first('phone_number')) }}</strong>
          </span>
          @endif
        </div>
      </div>

      <div class="col-md-6">
        <div class="customForm-group">
          {!! Form::text('country', Input::old('country'), ['class'=>'customInput', 'placeholder'=>t('Country of residence')]) !!}
          <span class="focus-border"></span>
          <i class="fa fa-globe"></i>
        </div>
      </div>

      <div class="col-md-6">
        <div class="customForm-group">
          {!! Form::select('native_language', Config::get('constant.Native Language'),null, ['class'=>'customSelect w-100', 'placeholder'=>t('Native Language')]) !!}
        </div>
      </div>

      <div class="col-md-6">
        <div class="customForm-group">
          {!! Form::text('', null, ['class'=>"customInput", 'placeholder'=>t("Attach Profile Picture")])!!}
          <span class="focus-border"></span>
          <label class="d-inline">
            <i class="fa fa-paperclip"></i>
            <input type="file" id="profile-img" name="profile_image" style="display: none;">
          </label>
          @if ($errors->has('profile_image'))
          <span class="help-block">
            <strong>{{ t($errors->first('profile_image')) }}</strong>
          </span>
          @endif
        </div>
      </div>

      <div class="col-md-6">
        <div class="customForm-group">
          {!! Form::password('password', ['class'=>'customInput','id'=>"password-field", 'placeholder'=>'Password']) !!}
          <span class="focus-border"></span>
          <i toggle="#password-field" class="fa fa-eye toggle-password" style="cursor:pointer"></i>
          @if ($errors->has('password'))
          <span class="help-block">
            <strong>{{ t($errors->first('password')) }}</strong>
          </span>
          @endif
        </div>

      </div>

      <div class="col-md-6">
        <div class="customForm-group">
          {!! Form::password('password_confirmation', ['class'=>'customInput', 'id'=>"password-field2", 'placeholder'=>'Confirm Password']) !!}
          <span class="focus-border"></span>
          <i toggle="#password-field2" class="fa fa-eye toggle-password2" style="cursor:pointer"></i>
        </div>
      </div>

    </div>
    <img src="" id="profile-img-tag" width="200px" class="img-thumbnail" />
    <div class="mt-4">
      <button type="submit" class="orangeBtn waves-effect waves-light">SIGN UP</button>
    </div>
    <div class="font14 mt-4">
      <span class="whiteText">Already have an account</span><a href="{!! lang_route('login')!!}"> Login</a>
    </div>
    {!! Form::close() !!}
  </div>
</div>
{!! HTML::script('assets/js/login.js') !!}
@endsection
