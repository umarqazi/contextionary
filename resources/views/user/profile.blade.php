@extends('layouts.secured_header')

@section('content')
<div class="container-fluid contributorMain userProfile">
    <div class="row">
        <div class="col-md-12">
            <div class="tabsContainer">
                <ul class="customTabs">
                    <li class="active title">My profile <a href="#"><i class="fas fa-pencil-alt"></i></a></li>
                </ul>
                <div class="searchHolder light">
                    <i class="fa fa-search"></i>
                    <input type="search" class="fld" placeholder="Search">
                </div>
            </div>
        </div>
        @if (Session::has('message'))
            <div class="alert alert-info">{{ Session::get('message') }}</div>
        @endif
        <div class="col-md-8 col-lg-8">
            <div class="userBlock">
                <div class="img-holder">
                    <img src="{!! asset('storage/').'/'.Auth::user()->profile_image !!}">
                </div>
                <div class="basicInfo">
                    <ul>
                        <li>First Name: <span>{!! $user['first_name']!!}</span></li>
                        <li>Last Name: <span>>{!! $user['last_name']!!}</span></li>
                        <li>Pseudonyme: <span>{!! $user['profile']['pseudonyme']!!}</span></li>
                        <li>Sex: <span>{!! $user['profile']['gender']!!}</span></li>
                        <li>Phone No: <span>{!! $user['profile']['phone_number']!!}</span></li>
                        <li>Native Language: <span>{!! $user['profile']['native_language']!!}</span></li>
                        <li>Country: <span>{!! $user['profile']['country']!!}</span></li>
                        <li>Email: <span>{!! $user['email']!!}</span></li>
                    </ul>
                </div>
                @if($user['user_profile']['bio'])
                  <div class="bio">
                      <p><strong>Bio:</strong></p>
                      <p>{!! $user['user_profile']['bio']!!}</p>
                  </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
