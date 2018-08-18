@extends('layouts.signup_view')
@section('title')
    {!! t('Contributor Select Role') !!}
@stop
@section('content')
    <div class="col-md-10 text-center">
        <div class="blockStyle wow fadeIn" data-wow-delay="0.6s">
            <h2 class="text-center">contributor plan</h2>
            <div class="row">
                @include('layouts.toaster')
                <div class="col-md-12">
                    {!! Form::open(['url'=>lang_route("saveContributor"), 'enctype'=>'multipart/form-data', 'method'=>'post']) !!}
                    {!! Form::hidden('user_id', $id,[]) !!}
                    <div class="planBlock form-container active desired-rolls">
                        <h3>Select Your Desired role/s</h3>
                        <div class="row justify-content-center">
                            <div class="col-md-2">
                                <div class="md-form ml-4 mt-0">
                                    <div class="custom-control custom-checkbox">
                                        {!! Form::checkbox('role[]', Config::get('constant.contributorRole.definition'),null,['class'=>'custom-control-input', 'id'=>'checkBoxw']) !!}
                                        <label class="custom-control-label" for="checkBoxw">{!! t('Writer') !!}</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="md-form ml-4 mt-0">
                                    <div class="custom-control custom-checkbox">
                                        {!! Form::checkbox('role[]', Config::get('constant.contributorRole.illustrator'),null,['class'=>'custom-control-input', 'id'=>'checkBoxi']) !!}
                                        <label class="custom-control-label" for="checkBoxi">{!! t('Illustrator') !!}</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="md-form ml-4 mt-0">
                                    <div class="custom-control custom-checkbox">
                                        {!! Form::checkbox('role[]', Config::get('constant.contributorRole.translator'),null,['class'=>'custom-control-input', 'id'=>'checkBoxT']) !!}
                                        <label class="custom-control-label" for="checkBoxT">{!! t('Translator') !!}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a href="#" class="orangeBtn mt-4 waves-light goto-fimiliar">{!! t('Continue') !!}</a>
                    </div>
                    <div class="col-md-12 form-container select-fimiliar">
                        <div class="planBlock ">
                            <h3>Select Familiar contexts</h3>
                            <div class="row">
                                @foreach($contextList as $key=>$context)
                                    <div class="col-md-4">
                                        <div class="md-form ml-4 mt-0">
                                            <div class="custom-control custom-checkbox">
                                                {!! Form::checkbox('context[]',$context['context_id'],null,['class'=>'custom-control-input', 'id'=>'checkBox'.$key]) !!}
                                                <label class="custom-control-label" for="checkBox{!! $key !!}">{!! t($context['context_name']) !!}</label>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <a href="#" class="orangeBtn mt-4 waves-light back-to">{!! t('Back') !!}</a>
                            <a href="#" class="orangeBtn mt-4 waves-light goto-lang">{!! t('Continue') !!}</a>
                        </div>
                    </div>
                    <div class="row wow fadeIn form-container select-lang" data-wow-delay="0.6s">
                        <div class="col-md-12">
                            <div class="planBlock">
                                <h3>Select the language for which you have the most advance level proficiency</h3>
                                <div class="row justify-content-center">
                                    <div class="col-md-2">
                                        <div class="custom-control custom-radio d-block my-2">
                                            {!! Form::radio('language', 'Chinese',null,['class'=>'custom-control-input', "id"=>"customRadio1"]) !!}
                                            <label class="custom-control-label" for="customRadio1">{!! t('Chinese') !!}</label>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="custom-control custom-radio d-block my-2">
                                            {!! Form::radio('language', 'Spanish',null,['class'=>'custom-control-input', "id"=>"customRadio2"]) !!}
                                            <label class="custom-control-label" for="customRadio2">{!! t('Spanish') !!}</label>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="custom-control custom-radio d-block my-2">
                                            {!! Form::radio('language', 'Hindi',null,['class'=>'custom-control-input', "id"=>"customRadio3"]) !!}
                                            <label class="custom-control-label" for="customRadio3">{!! t('Hindi') !!}</label>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="custom-control custom-radio d-block my-2">
                                            {!! Form::radio('language', 'French',null,['class'=>'custom-control-input', "id"=>"customRadio4"]) !!}
                                            <label class="custom-control-label" for="customRadio4">{!! t('French') !!}</label>
                                        </div>
                                    </div>

                                </div>
                                <a href="#" class="orangeBtn mt-4 waves-light back-to">{!! t('Back') !!}</a>
                                <button type="submit" class="orangeBtn mt-4 waves-light">{!! t('Subscribe') !!}</button>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    {!! HTML::script('assets/js/user/contributor.js') !!}
@endsection