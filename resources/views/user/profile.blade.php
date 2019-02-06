@extends('layouts.secured_header')
@section('title')
    {!! t('Profile') !!}
@stop
@section('content')
<div class="container-fluid contributorMain userProfile">
    @include('layouts.flc_header')
    <div class="row">
        <div class="col-md-12">
            @include('layouts.toaster')
        </div>
        <div class="col-md-9 col-lg-9">
            <div class="userBlock">
                <div class="img-holder">
                    @if(Auth::user()->profile_image)
                        <img src="{!! asset('storage/').'/'.Auth::user()->profile_image !!}">
                    @else
                        <img src="{!! asset('assets/images/default.jpg')!!}">
                    @endif
                </div>
                <div class="basicInfo">
                    <ul>
                        <li>{!! t('First Name') !!}: <span>{!! $user['first_name']!!}</span></li>
                        @if($user['last_name'])<li>{!! t('Last Name') !!}: <span>{!! $user['last_name']!!}</span></li>@endif
                        @if($user['profile']['pseudonyme'])<li>{!! t('Pseudonyme') !!}: <span>{!! $user['profile']['pseudonyme']!!}</span></li>@endif
                        @if($user['profile']['gender'])<li>{!! t('Sex') !!}: <span>{!! $user['profile']['gender']!!}</span></li>@endif
                        @if($user['profile']['native_language'])<li>{!! t('Native Language') !!}: <span>{!! $user['profile']['native_language']!!}</span></li>@endif
                        @if($user['profile']['country'])<li>{!! t('Country') !!}: <span>{!! $user['profile']['country']!!}</span></li>@endif
                        <li>{!! t('Email') !!}: <span>{!! $user['email']!!}</span></li>
                    </ul>
                </div>
                @if($user->profile->bio)
                  <div class="bio">
                      <p><strong>Bio:</strong></p>
                      <p>{!! $user->profile->bio!!}</p>
                  </div>
                @endif
                <a href="{!! lang_route('edit-profile') !!}" class="orangeBtn mt-4">{!! t('Edit Profile') !!}</a>
            </div>
        </div>

    </div>
</div>
@endsection
