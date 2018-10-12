@include('layouts.toaster')
<div class="card-container">
    <div class='form-row1'>
        <div class='col-md-12 form-group required'>
            <label class='control-label'>Card Number</label>
            {!! Form::text('card_no', '', ['class'=>'customInput card-number', 'maxlength'=>'20', 'placeholder'=>'Card Number'])!!}
            @if ($errors->has('card_no'))
                <div class="help-block"><strong>{{ $errors->first('card_no') }}</strong></div>
            @endif
        </div>
    </div>
    <div class='form-row1'>
        <div class='col-md-4 form-group cvc required'>
            <label class='control-label'>CVV</label>
            {!! Form::text('cvvNumber', '', ['class'=>'customInput card-cvc', 'maxlength'=>'4', 'placeholder'=>'ex. 311'])!!}
            @if ($errors->has('cvvNumber'))
                <div class="help-block"><strong>{{ $errors->first('cvvNumber') }}</strong></div>
            @endif
        </div>
        <div class='col-md-4 form-group expiration required'>
            <label class='control-label'>Expiration</label>
            {!! Form::text('ccExpiryMonth', '', ['class'=>'customInput card-expiry-month', 'maxlength'=>'2', 'placeholder'=>'MM'])!!}
            @if ($errors->has('ccExpiryMonth'))
                <div class="help-block"><strong>{{ $errors->first('ccExpiryMonth') }}</strong></div>
            @endif
        </div>
        <div class='col-md-4 form-group expiration required'>
            <label class='control-label'>&nbsp;</label>
            {!! Form::text('ccExpiryYear', '', ['class'=>'customInput card-expiry-year', 'maxlength'=>'4', 'placeholder'=>'YYYY'])!!}
            @if ($errors->has('ccExpiryYear'))
                <div class="help-block"><strong>{{ $errors->first('ccExpiryYear') }}</strong></div>
            @endif
        </div>
    </div>
</div>