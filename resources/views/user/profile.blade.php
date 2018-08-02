@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                @if (Session::has('message'))
                    <div class="alert alert-info">{{ Session::get('message') }}</div>
                @endif
                <div class="panel panel-default">
                    <div class="panel-heading">
                            <h4>Profile <a href="{{route('edit profile')}}" class="btn btn-sm btn-primary pull-right">Edit Profile</a></h4>
                    </div>

                    <div class="panel-body">
                            <div>
                                <label for="name" class="col-md-4 control-label">Name</label>
                                <div class="col-md-6">
                                    <p>{{$user->name}}</p>
                                </div>
                            </div>
                            <div>
                                <label for="email" class="col-md-4 control-label">E-Mail Address</label>
                                <div class="col-md-6">
                                    <p>{{$user->email}}</p>
                                </div>
                            </div>
                            <div>
                                <label for="role" class="col-md-4 control-label">User Type</label>
                                <div class="col-md-6">
                                    <p>{{ ucfirst($user->getRoleNames()[0]) }}</p>
                                </div>
                            </div>
                            <div>
                                <label for="role" class="col-md-4 control-label">Joined On</label>
                                <div class="col-md-6">
                                    <p>{{ $user->created_at }}</p>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection