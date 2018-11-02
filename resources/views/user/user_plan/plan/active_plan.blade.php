@extends('layouts.secured_header')
@section('title')
    {!! t('Plans') !!}
@stop
@section('content')
    <div class="container-fluid contributorMain userPlanBg">
        <div class="wrapperMask"></div>
        <div class="row">
            <div class="col-md-12">
                <div class="tabsContainer">
                    <ul class="customTabs tabsView">
                        <li class="active"><a href="#">User plan</a></li>
                    </ul>
                    <div class="searchHolder light">
                        <i class="fa fa-search"></i>
                        <input type="search" class="fld" placeholder="Search">
                    </div>
                </div>
            </div>

        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                @if($activePlan)
                    @if($activePlan->expiry_date == '')
                        <div class="planTitle">
                            {!! t('Current plan') !!} <span>({!! t('remaining time') !!}: {{t('Unlimited')}} {{ t('days') }})</span>
                        </div>
                    @elseif($activePlan->expiry_date < carbon::now())
                        <div class="planTitle">
                            {!! t('Current plan') !!} <span>({!! t('Expired') !!})</span>
                        </div>
                    @elseif($activePlan->expiry_date >= carbon::now())
                        <div class="planTitle">
                            {!! t('Current plan') !!} <span>({!! t('remaining time') !!}: {!! $days !!} {{ t(str_plural('day', $days)) }})</span>
                        </div>
                    @endif
                @else
                    {{--<div class="planTitle">--}}
                    {{--{!! t('Current plan') !!} <span>({!! t('remaining time') !!}: {!! $days !!} {{ t(str_plural('day', $days)) }})</span>--}}
                    {{--</div>--}}
                    @if($total_contribution >= 3)
                        <div class="planTitle">
                            {!! t('Current plan') !!} <span>({!! t('Free Unlimited Access') !!})</span>
                        </div>
                    @endif
                @endif
            </div>

            <div class="col-lg-8">
                @include('layouts.toaster')
                @if($activePlan)
                    <div class="planBlock">
                        <div class="img-holder">
                            <img src="{!! asset('assets/images/plan').$activePlan->package_id.'.png' !!}">
                        </div>
                        <div class="planInfo">
                            <div class="activePlan"><i class="fa fa-certificate"></i> amount / month: ${!! Config::get('constant.plan_prices.'.$activePlan->package_id) !!}</div>
                            <h2>Features</h2>
                            <ul class="features">
                                <li>Reading assistant<br>
                                    <span>Context,</span>
                                    <span>Keywords,</span>
                                    <span>Definition,</span>
                                    <span>Illustration,</span>
                                    <span>Related words</span>
                                    @if($activePlan->package_id!=1)
                                        <span>Export Results</span>
                                    @endif
                                    @if($activePlan->package_id==2)
                                        <span>File Upload</span>
                                    @endif
                                </li>
                                <li>Glossary catalog</li>
                                <li>A game of context</li>
                                @if($activePlan->package_id==2)
                                    <li>Learning Center</li>
                                @endif
                            </ul>
                            @if($activePlan->expiry_date == '')
                            @elseif($activePlan->expiry_date <= carbon::now())
                                @if($activePlan->total_contribution >= 3)
                                @else
                                <a href="{!! lang_url('payment', ['id'=>$activePlan->package_id]) !!}" class="orangeBtn mb-3 mt-3">Renew Plan</a>
                                @endif
                            @elseif($activePlan->expiry_date > carbon::now())
                                @if($activePlan->total_contribution >= 3)
                                @else
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name='auto' id="check_box_auto_renew"
                                        @if($activePlan->auto) checked @endif>
                                        <label class="custom-control-label" for="check_box_auto_renew">Activate auto renewal</label>
                                    </div>
                                @endif
                            @endif
                        </div>
                        <div class="row card-div ">
                            @if(!$cards->isEmpty())
                                <div class="col-md-12">
                                    <h2>My payments</h2>
                                </div>
                                @foreach($cards as $card)
                                    <div class="col-md-4">
                                        <div class="cardBlock">
                                            <img src="{!! asset('assets/images/').'/'.strtolower($card['brand']).'-card.png'!!}">
                                            <a href="{!! lang_url('delete-card', ['id'=>$card['id']]) !!}" class="btn del-btn"><i class="fa fa-trash"></i></a>
                                            <p>XXXXXXXXXXXX{!! $card['last4'] !!}<br>
                                                <span>{!! Auth::user()->first_name !!} <span class="float-right">{!! $card['exp_month'] !!}/{!! $card['exp_year'] !!}</span></span>
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @else
                    @if($total_contribution >= 3)
                        <h3>{{t('You are given free access of the premium plan.')}}</h3>
                    @endif
                @endif
                <div class="planTitle">{!! t('other plans') !!}</div>
                @php
                    $packages = Config::get('constant.packages');
                    array_pop($packages);
                @endphp
                @foreach($packages as $key=>$package)
                    @if($activePlan)
                        @if($key!=$activePlan->package_id)
                            <div class="planBlock">
                                <div class="img-holder">
                                    <img src="{!! asset('assets/images/plan').$key.'.png' !!}">
                                </div>
                                <div class="planInfo">
                                    <div class="activePlan"><i class="fa fa-certificate"></i> amount / month: ${!! Config::get('constant.plan_prices.'.$key) !!}</div>
                                    <h2>Features</h2>
                                    <ul class="features">
                                        <li>Reading assistant<br>
                                            <span>Context,</span>
                                            <span>Keywords,</span>
                                            <span>Definition,</span>
                                            <span>Illustration,</span>
                                            <span>Related words</span>
                                            @if($key!=1)
                                                <span>Export Results</span>
                                            @endif
                                            @if($key==2)
                                                <span>File Upload</span>
                                            @endif
                                        </li>
                                        <li>Glossary catalog</li>
                                        <li>A game of context</li>
                                        @if($key==2)
                                            <li>Learning Center</li>
                                        @endif
                                    </ul>

                                    <a href="{!! lang_url('payment', ['id'=>$key]) !!}" class="orangeBtn mb-3 mt-3">Purchase</a>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="planBlock">
                            <div class="img-holder">
                                <img src="{!! asset('assets/images/plan').$key.'.png' !!}">
                            </div>
                            <div class="planInfo">
                                <div class="activePlan"><i class="fa fa-certificate"></i> amount / month: ${!! Config::get('constant.plan_prices.'.$key) !!}</div>
                                <h2>Features</h2>
                                <ul class="features">
                                    <li>Reading assistant<br>
                                        <span>Context,</span>
                                        <span>Keywords,</span>
                                        <span>Definition,</span>
                                        <span>Illustration,</span>
                                        <span>Related words</span>
                                        @if($key!=1)
                                            <span>Export Results</span>
                                        @endif
                                        @if($key==2)
                                            <span>File Upload</span>
                                        @endif
                                    </li>
                                    <li>Glossary catalog</li>
                                    <li>A game of context</li>
                                    @if($key==2)
                                        <li>Learning Center</li>
                                    @endif
                                </ul>

                                <a href="{!! lang_url('payment', ['id'=>$key]) !!}" class="orangeBtn mb-3 mt-3">Purchase</a>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    {!! HTML::script(asset('assets/js/toaster.js')) !!}

    @if($activePlan)
    <script type="text/javascript">
        $(document).ready(function() {
            $('#check_box_auto_renew').change(function() {
                $('.default-loader').css('display', 'block');
                if($(this).is(":checked")) {
                    $.ajax({
                        type    : "POST",
                        url     : "{!! lang_url('autopay', ['id'=>$activePlan->package_id]) !!}",
                        data    : { _token: '{{csrf_token()}}'},
                        async   : false,
                    }).done(function( res ) {
                        if(res == 1){
                            $('.default-loader').css('display', 'none');
                            toastr.success("{{ t('Auto Renewal Active') }}");
                        }
                    });
                }
                else {
                    $.ajax({
                        type    :   "POST",
                        url     :   "{!! lang_url('cancelautopay') !!}",
                        data    :   { _token: '{{csrf_token()}}'},
                        async   :   false,
                    }).done(function( res ) {
                        if(res == 1){
                            $('.default-loader').css('display', 'none');
                            toastr.success("{{ t('Auto Renewal Disabled') }}");
                        }
                    });
                }
            });
        });
    </script>
    @endif
@endsection
