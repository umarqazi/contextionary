@if($coins >=1 )
    <div class="row mt-4">
        <div class="col-md-12 mt-4 text-center actionsBtn bid-div">
            <div class="coinsWrapper">
                <span class="white-text">{!! t('Available Coins') !!}:  <span class="green-color">{!! $coins  !!}</span></span>
                <button type="button" class="sub"><i class="fa fa-minus"></i></button>
                {!! Form::number('bid', null,['class'=>'coins', 'max'=>($coins!=0)? $coins:'', 'onkeyup'=>"restrictBid(this)"]) !!}
                <button type="button" class="add"><i class="fa fa-plus"></i></button>
            </div>
            <button type="submit" class="orangeBtn ml-3 waves-light bidBtn @if($coins==0) grey @endif" @if($coins==0) disabled @endif id="bid-button">{!! t('Bid') !!}</button>

            @if(\Route::current()->getName() == App::getLocale().'.defineMeaning')
                <a href="{!! lang_route('editMeaning', ['context_id'=>$data['context_id'], 'phrase_id'=>$data['phrase_id'],'id'=>$data['id']]) !!}" class="orangeBtn ml-3 waves-light bidBtn">{!! t('Edit') !!}</a>
                <a href="{!! lang_url('define')!!}" class="orangeBtn ml-3 waves-light bidBtn waves-effect waves-light">{!! t('Return') !!}</a>
            @else
                <button type="button" onclick="showButtons()" class="orangeBtn ml-3 waves-light bidBtn">{!! t('Edit') !!}</button>
                <a href="{!! URL::previous()!!}" class="orangeBtn ml-3 waves-light bidBtn waves-effect waves-light">{!! t('Return') !!}</a>
            @endif
            <div class="col-md-12 mt-4 text-center actionsBtn bid-div">
                @if ($errors->has('bid'))
                    <div class="help-block"><strong>{{ $errors->first('bid') }}</strong></div>
                @endif
            </div>
        </div>
    </div>
@else
    <div class="row mt-4">
        <div class="col-md-12 mt-4 text-center actionsBtn bid-div">
            <div class="coinsWrapper">
                <p class="customLabel ">{!! t('You have 0 coin in your wallet') !!}</p>
                <a href="{!! lang_url('coins-list') !!}" class="orangeBtn ml-3 waves-light bidBtn">{!! t('Click Here') !!}</a>
            </div>
        </div>
    </div>
@endif