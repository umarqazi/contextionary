@extends('layouts.secured_header')
@section('title')
    {!! t('Edit Role') !!}
@stop
@section('content')
    <div class="container-fluid contributorMain userProfile">
        @include('layouts.flc_header')
        <div class="col-md-12 text-center">
            <div class="rolesblock">
                <div class="row">
                    @include('layouts.toaster')
                    <div class="col-md-12">
                        {!! Form::open(['url'=>lang_route("saveContributor"), 'enctype'=>'multipart/form-data', 'method'=>'post']) !!}
                        {!! Form::hidden('user_id', $id,[]) !!}
                        {!! Form::hidden('profile', '1',[]) !!}
                        <div class="planBlock desired-roles">
                            <h3 class="heading-color">{!! t('Select Your Desired role/s') !!}</h3>
                            <div class="row justify-content-center">
                                @php
                                    $roles_names = array_column($roles, 'selected');
                                @endphp
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
                                    <h3 class="heading-color">{!! t('You would like to contribute for') !!}</h3>
                                    <div class="row">
                                        @if($contextList)
                                            @foreach($contextList as $key=>$context)
                                                @if($context['context_id'] >= 4)
                                                    <div class="col-md-4">
                                                        <div class="md-form text-left mb-0 mt-0">
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
                        </div>
                        <div id="desired-roles-translate" class="desired-roles desired-roles-translate @if(!in_array ( 'translate', $roles_names )) hidden @endif">
                            <div class="planBlock">
                                <h3 class="heading-color">{!! t('You would like to translate from English to') !!}</h3>
                                <div class="row justify-content-center">
                                    <div class="col-md-3">
                                        <div class="custom-control custom-radio d-block my-2">
                                            {!! Form::radio('language', 'Chinese',null,[($language=='Chinese')? 'checked':'','class'=>'custom-control-input', "id"=>"customRadio1"]) !!}
                                            <label class="custom-control-label" for="customRadio1">{!! t('Simplified Chinese') !!}</label>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="custom-control custom-radio d-block my-2">
                                            {!! Form::radio('language', 'Spanish',null,[($language=='Spanish')? 'checked':'','class'=>'custom-control-input', "id"=>"customRadio2"]) !!}
                                            <label class="custom-control-label" for="customRadio2">{!! t('Spanish') !!}</label>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="custom-control custom-radio d-block my-2">
                                            {!! Form::radio('language', 'Hindi',null,[($language=='Hindi')? 'checked':'','class'=>'custom-control-input', "id"=>"customRadio3"]) !!}
                                            <label class="custom-control-label" for="customRadio3">{!! t('Hindi') !!}</label>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="custom-control custom-radio d-block my-2">
                                            {!! Form::radio('language', 'French',null,[($language=='French')? 'checked':'', 'class'=>'custom-control-input', "id"=>"customRadio4"]) !!}
                                            <label class="custom-control-label" for="customRadio4">{!! t('French') !!}</label>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="mb-5">
                            <a href="{!! URL::previous() !!}" class="orangeBtn mt-4 waves-light back-to make-vertical ">{!! t('Back') !!}</a>
                            <button type="submit" class="orangeBtn mt-4 waves-light make-vertical">{!! t('Update') !!}</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! HTML::script('assets/js/user/roles.js') !!}
@endsection
