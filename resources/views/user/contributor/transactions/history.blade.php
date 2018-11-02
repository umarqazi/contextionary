@extends('layouts.secured_header')
@section('title')
    {!! t('Contributor History') !!}
@stop
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="container-fluid contributorMain purchasCoinBg">
                @include('layouts.flc_header')
                @include('layouts.toaster')
                <div class="row mt-4">
                    {!! Form::open(['url'=>lang_route('search'), 'method'=>'post', 'id'=>'form-submission']) !!}
                    <div class="col-md-12">
                        <table class="table table-striped">
                            <tr>
                                <td>{!! Form::select('type', [''=>'Search by Type','writer'=>'Writer', 'illustrator'=>'Illustrator', 'translator'=>'Translator'], null,['class'=>'customSelect w-100']) !!}</td>
                                <td>{!! Form::select('positions', [''=>'Search by Position','1'=>'First', '2'=>'Second', '3'=>'Third'], null,['class'=>'customSelect w-100']) !!}</td>
                                <td>{!! Form::select('status', [''=>'Search by Status','0'=>'Pending', '1'=>'Accepted', '2'=>'Rejected', '3'=>'Completed'], null,['class'=>'customSelect w-100']) !!}</td>
                                <td>{!! Form::submit('Search', ['class'=>'orangeBtn ml-3 waves-effect']) !!}</td>
                                <td><a href="{!! lang_url('user-history') !!}" class='orangeBtn ml-3 waves-effect'>{!! t('Reset Filter') !!}</a> </td>
                            </tr>
                        </table>
                    </div>
                    {!! Form::close() !!}
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>{!! t('Date') !!}</th>
                                    <th>{!! t('Contribution') !!}</th>
                                    <th>{!! t('Type') !!}</th>
                                    <th>{!! t('Context Name') !!}</th>
                                    <th>{!! t('Phrase Name') !!}</th>
                                    <th>{!! t('Position') !!}</th>
                                    <th>{!! t('Points') !!}</th>
                                    <th>{!! t('Coins') !!}</th>
                                    <th>{!! t('Status') !!}</th>
                                    <th>{!! t('Action') !!}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!$history->isEmpty())
                                    @foreach($history as $userHistory)
                                        <?php
                                        if($userHistory['status']==1 || $userHistory['status']==3):
                                            $class='text-success';
                                        elseif($userHistory['status']==0):
                                            $class='text-info';
                                        else:
                                            $class='text-danger';
                                        endif;
                                        ?>
                                        <tr>
                                            <td>{!! $userHistory['date'] !!}</td>
                                            <td>@if($userHistory['type']=='illustrator') <img src="{!! asset('storage') !!}/{!! $userHistory['contribution']  !!}" class="img-thumbnail"> @else {!! substr($userHistory['contribution'],0,150) !!}@endif</td>
                                            <td><span class="text-capitalize">{!! $userHistory['type'] !!}</span></td>
                                            <td class="text-capitalize">{!! $userHistory['context_name'] !!}</td>
                                            <td class="text-capitalize">{!! $userHistory['phrase_name'] !!}</td>
                                            <td>{!! ($userHistory['position'])? Config::get('constant.position.'.$userHistory['position']):'N/A' !!}</td>
                                            <td>{!! ($userHistory['point']) !!}</td>
                                            <td>{!! $userHistory['coins'] !!}</td>
                                            <td><strong class="{!! $class !!}">{!! Config::get('constant.status.'.$userHistory['status']) !!}</strong></td>
                                            <td>@if($userHistory['route'])<a href="{!!  $userHistory['route']!!}" class="btn btn-primary">View</a>@endif</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="9">
                                            <div class="col-md-12">
                                                <div class="text-center">
                                                    <strong class="record-message">{!! t('No Contribution Yet') !!}</strong>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                            <div class="col-md-12 mt-4 mb-4 text-center">
                                <div class="customPagination">
                                    {!! $history->links() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! HTML::script('assets/js/user/purchase_coins.js') !!}
@endsection
