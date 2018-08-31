@extends('layouts.secured_header')
@section('title')
    {!! t('Edit Profile') !!}
@stop
@section('content')
    <div class="container-fluid contributorMain userProfile">
        <div class="row">
            <div class="col-md-12">
                <div class="tabsContainer">
                    <ul class="customTabs">
                        <li class="active title">{!! t('My Profile') !!} <a href="{!! lang_route('edit profile') !!}"><i class="fas fa-pencil-alt"></i></a></li>
                    </ul>
                    @include('search')
                </div>
            </div>
        </div>
        @if (Session::has('message'))
            <div class="alert alert-info">{{ Session::get('message') }}</div>
        @endif
        <form class="form-horizontal" enctype="multipart/form-data" method="post" action="{{ lang_url('update-profile') }}">
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
                        <input type="text" class="customSelect w-100" name="pseudonyme" value="{{ $user->profile->pseudonyme }}" required autofocus>

                        @if ($errors->has('pseudonyme'))
                            <span class="help-block">
                                <strong>{{ $errors->first('pseudonyme') }}</strong>
                            </span>
                        @endif
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
                        <input type="text" class="customSelect w-100" name="country" value="{{ $user->profile->country }}" required>
                        @if ($errors->has('country'))
                            <span class="help-block">
                                <strong>{{ $errors->first('country') }}</strong>
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
                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        <button type="submit" class="btn btn-primary pull-right">
                            Update
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
