@extends('layouts.signup_view')
@section('title')
  {!! t('Sign Up') !!}
@stop
@section('content')
  <?php $countries=Config::get('countries.countries');ksort($countries);?>
  <div class="col-md-10 text-center">
    <div class="blockStyle wow fadeIn" data-wow-delay="0.6s">
      @include('layouts.toaster')
      <h2>{!! t('SIGN UP') !!}</h2>
      {!! Form::open(['url'=>lang_route("register"),'enctype'=>'multipart/form-data', 'method'=>'post', 'id'=>'form-submission']) !!}
      <div class="row">
        <div class="col-md-6">
          <div class="customForm-group">
            {!! Form::text('first_name', Input::old('first_name'), ['class'=>'customInput', 'placeholder'=>t('First Name')]) !!}
            <span class="focus-border"></span><span class="asterick">*</span>
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
            <span class="focus-border"></span><span class="asterick">*</span>
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
          <div class="customForm-group"><span class="asterick">*</span>
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
            <span class="focus-border"></span><span class="asterick">*</span>
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
            {!! Form::select('country', $countries, Input::old('country'), ['class'=>'customSelect w-100', 'placeholder'=>t('Country of Residence')]) !!}
            <span class="focus-border"></span>
          </div>
        </div>

        <div class="col-md-6">
          <div class="customForm-group"><span class="asterick">*</span>
            {!! Form::select('timezone', Config::get('countries.timezone'), Input::old('timezone'), ['class'=>'customSelect w-100', 'placeholder'=>t('Timezone')]) !!}
            <span class="focus-border"></span>
          </div>
          @if ($errors->has('timezone'))
            <span class="help-block">
            <strong>{{ t($errors->first('timezone')) }}</strong>
          </span>
          @endif
        </div>

        <div class="col-md-6">
          <div class="customForm-group"><span class="asterick">*</span>
            {!! Form::select('native_language', Config::get('constant.Native Language'),null, ['class'=>'customSelect w-100', 'placeholder'=>t('Native Language')]) !!}
          </div>
          @if ($errors->has('native_language'))
            <span class="help-block">
            <strong>{{ t($errors->first('native_language')) }}</strong>
          </span>
          @endif
        </div>

        <div class="col-md-6">
          <div class="customForm-group">
            {!! Form::text('', null, ['class'=>"customInput", 'placeholder'=>t("Attach Profile Picture")])!!}
            <span class="focus-border"></span>
            <label class="d-inline attach-image">
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
        <button type="submit" class="orangeBtn waves-effect waves-light">{!! t('Sign Up') !!}</button>
      </div>
      <div class="font14 mt-4">
        <span class="whiteText">{!! t('Already Have An Account?') !!}</span><a href="{!! lang_route('login')!!}"> {!! t('Login') !!}</a>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
  {!! HTML::script('assets/js/login.js') !!}
@endsection
