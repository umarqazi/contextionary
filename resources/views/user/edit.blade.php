@extends('layouts.secured_header')
@section('title')
    {!! t('Edit Profile') !!}
@stop
@section('content')
    <div class="container-fluid contributorMain userProfile">
        @include('layouts.flc_header')
        @include('layouts.toaster')
        <form class="form-horizontal" enctype="multipart/form-data" method="post" action="{{ lang_url('update-profile') }}" id='form-submission'>
            <div class="row">

                {{ csrf_field() }}
                <div class="col-md-6 make-left">

                    <div class="customForm-group">
                        <input type="text" class="customSelect w-100" name="first_name" value="{{ $user->first_name }}" required autofocus>

                        @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6 make-left">

                    <div class="customForm-group">
                        <input type="text" class="customSelect w-100" name="last_name" value="{{ $user->last_name }}" required autofocus>

                        @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6 make-left">

                    <div class="customForm-group">
                        <input type="email" class="customSelect w-100" name="email" value="{{ $user->email }}" required>

                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6 make-left">

                    <div class="customForm-group">
                        <input type="text" placeholder="{!! t('Pseudonyme') !!}" class="customSelect w-100" name="pseudonyme" value="{{ $user->profile->pseudonyme }}" autofocus>

                        @if ($errors->has('pseudonyme'))
                            <span class="help-block">
                                <strong>{{ $errors->first('pseudonyme') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6 make-left">
                    <div class="customForm-group">
                        <div class="input-group with-addon-icon-left customDatePicker">
                            <input type="text" value="{!! $user->profile->date_birth !!}" name="date_birth" class="form-control custom-fld" id="datepicker-example-1" placeholder={!! t('Date of birth') !!}>
                            <span class="input-group-append"><span class="input-group-text"><i class="fa fa-calendar"></i></span></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="customForm-group">
                        {!! Form::select('country', Config::get('countries.countries'),  $user->profile->country, ['class'=>'customSelect w-100', 'placeholder'=>t('Country of Residence')]) !!}
                        <span class="focus-border"></span>
                    </div>
                </div>
                <div class="col-md-6 make-left">

                    <div class="customForm-group">
                        {!! Form::select('gender', ['Male'=>'Male', 'Female'=>'Female'], $user->profile->gender, ['class'=>'customSelect w-100','placeholder'=>'Gender']) !!}
                        @if ($errors->has('gender'))
                            <span class="help-block">
                                <strong>{{ $errors->first('gender') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6 make-left">

                    <div class="customForm-group">
                        {!! Form::select('native_language', Config::get('constant.Native Language'),$user->profile->native_language, ['class'=>'customSelect w-100', 'placeholder'=>t('Native Language')]) !!}
                        @if ($errors->has('gender'))
                            <span class="help-block">
                                <strong>{{ $errors->first('gender') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6 make-left">

                    <div class="customForm-group">
                        <input type="text" class="customSelect w-100" name="phone_number" value="{{ $user->profile->phone_number }}" required>
                        @if ($errors->has('phone_number'))
                            <span class="help-block">
                                <strong>{{ $errors->first('phone_number') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6 make-left">

                    <div class="customForm-group">
                        {!! Form::textarea('bio', $user->profile->bio, ['class'=>'customSelect w-100', 'Placeholder'=>t('Enter Your Bio')]) !!}
                        @if ($errors->has('bio'))
                            <span class="help-block">
                                <strong>{{ $errors->first('bio') }}</strong>
                            </span>
                        @endif
                    </div>
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
                    <div class="customForm-group"><span class="asterick">*</span>
                        {!! Form::select('timezone', Config::get('countries.timezone'), $user->timezone, ['class'=>'customSelect w-100', 'placeholder'=>t('Timezone')]) !!}
                        <span class="focus-border"></span>
                    </div>
                    @if ($errors->has('timezone'))
                        <span class="help-block">
                                <strong>{{ $errors->first('timezone') }}</strong>
                            </span>
                    @endif
                </div>
                <div class="col-md-6">
                    <div class="customForm-group">
                        <input id="password" type="password" class="customSelect w-100" name="password" placeholder="{!! t('Change Password') !!}">

                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="customForm-group">
                        <input id="password-confirm" type="password" class="customSelect w-100" placeholder="{!! t('Confirm Password') !!}" name="password_confirmation">
                    </div>
                </div>
                <div class="col-md-12">
                    <img src="" id="profile-img-tag" width="200px" class="img-thumbnail" />

                </div>
                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        <button type="submit" class="btn btn-primary pull-right">
                            {!! t('Update') !!}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    {!! HTML::script('assets/js/login.js') !!}
@endsection
