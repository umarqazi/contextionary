@extends('layouts.secured_header')
@section('title')
    {!! t('Edit Profile') !!}
@stop
@section('content')
    <div class="container-fluid contributorMain userProfile">
        @include('layouts.profile-menu')
        <div class="col-md-12 text-center">
            <div class="rolesblock">
                <div class="row">
                    @include('layouts.toaster')
                    <div class="col-md-12">
                        {!! Form::open(['url'=>lang_route("saveContributor"), 'enctype'=>'multipart/form-data', 'method'=>'post']) !!}
                        {!! Form::hidden('user_id', $id,[]) !!}
                        {!! Form::hidden('profile', '1',[]) !!}
                        <div class="planBlock desired-roles">
                            <h3 class="heading-color">Select Your Desired role/s</h3>
                            <div class="row justify-content-center">
                                @foreach($roles as $key=>$role)
                                    <div class="col-md-2">
                                        <div class="md-form ml-4 mt-0">
                                            <div class="custom-control custom-checkbox">
                                                {!! Form::checkbox('role[]', $role['role'],$role['selected'],['class'=>'custom-control-input', 'id'=>'checkBox'.$key]) !!}
                                                <label class="custom-control-label" for="checkBox{!! $key !!}">{!! t($role['role']) !!}</label>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                            <div class="desired-roles">
                                <div class="planBlock ">
                                    <h3 class="heading-color">{!! t('Select Familiar contexts') !!}</h3>
                                    <div class="row">
                                        @if($contextList)
                                            @foreach($contextList as $key=>$context)
                                                @if($context['context_id'] >= 4)
                                                    <div class="col-md-4">
                                                        <div class="md-form ml-4 mt-0">
                                                            <div class="custom-control custom-checkbox">
                                                                {!! Form::checkbox('context[]',$context['context_id'],$context['selected'],['class'=>'custom-control-input', 'id'=>'checkContextBox'.$key]) !!}
                                                                <label class="custom-control-label" for="checkContextBox{!! $key !!}">{!! t($context['context_name']) !!}</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="desired-roles">
                                    <div class="planBlock">
                                        <h3 class="heading-color">{!! t('Select Language') !!}</h3>
                                        <div class="row justify-content-center">
                                            <div class="col-md-2">
                                                <div class="custom-control custom-radio d-block my-2">
                                                    {!! Form::radio('language', 'Chinese',$language,['class'=>'custom-control-input', "id"=>"customRadio1"]) !!}
                                                    <label class="custom-control-label" for="customRadio1">{!! t('Chinese') !!}</label>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="custom-control custom-radio d-block my-2">
                                                    {!! Form::radio('language', 'Spanish',$language,['class'=>'custom-control-input', "id"=>"customRadio2"]) !!}
                                                    <label class="custom-control-label" for="customRadio2">{!! t('Spanish') !!}</label>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="custom-control custom-radio d-block my-2">
                                                    {!! Form::radio('language', 'Hindi',$language,['class'=>'custom-control-input', "id"=>"customRadio3"]) !!}
                                                    <label class="custom-control-label" for="customRadio3">{!! t('Hindi') !!}</label>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="custom-control custom-radio d-block my-2">
                                                    {!! Form::radio('language', 'French',$language,['class'=>'custom-control-input', "id"=>"customRadio4"]) !!}
                                                    <label class="custom-control-label" for="customRadio4">{!! t('French') !!}</label>
                                                </div>
                                            </div>

                                        </div>
                                        <a href="{!! URL::previous() !!}" class="orangeBtn mt-4 waves-light back-to make-vertical ">{!! t('Back') !!}</a>
                                        <button type="submit" class="orangeBtn mt-4 waves-light make-vertical">{!! t('Update') !!}</button>
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
