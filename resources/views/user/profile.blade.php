@extends('layouts.secured_header')
@section('title')
    {!! t('Profile') !!}
@stop
@section('content')
<div class="container-fluid contributorMain userProfile">
    @include('layouts.profile-menu')
    <div class="row">
        <div class="col-md-12">
            @include('layouts.toaster')
        </div>
        <div class="col-md-8 col-lg-8">
            <div class="userBlock">
                <div class="img-holder">
                    <img src="{!! asset('storage/').'/'.Auth::user()->profile_image !!}">
                </div>
                <div class="basicInfo">
                    <ul>
                        <li>{!! t('First Name') !!}: <span>{!! $user['first_name']!!}</span></li>
                        <li>{!! t('Last Name') !!}: <span>{!! $user['last_name']!!}</span></li>
                        @if($user['profile']['pseudonyme'])<li>{!! t('Pseudonyme') !!}: <span>{!! $user['profile']['pseudonyme']!!}</span></li>@endif
                        <li>{!! t('Sex') !!}: <span>{!! $user['profile']['gender']!!}</span></li>
                        <li>{!! t('Phone No') !!}: <span>{!! $user['profile']['phone_number']!!}</span></li>
                        <li>{!! t('Native Language') !!}: <span>{!! $user['profile']['native_language']!!}</span></li>
                        <li>{!! t('Country') !!}: <span>{!! $user['profile']['country']!!}</span></li>
                        <li>{!! t('Email') !!}: <span>{!! $user['email']!!}</span></li>
                    </ul>
                </div>
                @if($user->profile->bio)
                  <div class="bio">
                      <p><strong>Bio:</strong></p>
                      <p>{!! $user->profile->bio!!}</p>
                  </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
