<div class="row mt-4">
    <div class="col-md-12 mt-4 text-center actionsBtn bid-div">
        <div class="coinsWrapper">
            <span class="white-text">{!! t('Available Coins') !!}:  <span class="green-color">{!! $coins  !!}</span></span>
            <button type="button" class="sub"><i class="fa fa-minus"></i></button>
            {!! Form::number('bid', '1',['class'=>'coins', 'min'=>'1']) !!}
            <button type="button" class="add"><i class="fa fa-plus"></i></button>
            @if ($errors->has('coins'))
                <div class="help-block"><strong>{{ $errors->first('coins') }}</strong></div>
            @endif
        </div>
        <button type="submit" class="orangeBtn ml-3 waves-light bidBtn">{!! t('Bid') !!}</button>
        @if(\Route::current()->getName() == App::getLocale().'.defineMeaning')
            <a href="{!! lang_route('editMeaning', ['context_id'=>$data['context_id'], 'phrase_id'=>$data['phrase_id'],'id'=>$data['id']]) !!}" class="orangeBtn ml-3 waves-light bidBtn">{!! t('Edit') !!}</a>
        @else
            <button type="button" onclick="showButtons()" class="orangeBtn ml-3 waves-light bidBtn">{!! t('Edit') !!}</button>
        @endif
    </div>
</div>