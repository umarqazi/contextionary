@extends('layouts.signup_view')
@section('title')
{!! t('User Plan') !!}
@stop
@section('content')
<div class="col-md-12 text-center">
  <div class="blockStyle wow fadeIn" data-wow-delay="0.6s">
    <div class="row">
      <div class="col-md-12">
        <div class="planBlock">
          <h3>User Plan</h3>

          <div class='pricing pricing-palden userPlan mt-5'>
            <div class='pricing-item'>
              <div class='pricing-deco'>

                <div class='pricing-price'><span class='pricing-currency'>$</span>2.99
                  <span class="pricing-period">/ month</span>
                </div>
                <h3 class='pricing-title'>Basic plan</h3>
              </div>
              <ul class='pricing-feature-list'>
                <li class='pricing-feature'>Reading assistant
                  <ul class="priceListing">
                    <li><i class="fa fa-check"></i> context</li>
                    <li><i class="fa fa-check"></i> keyword</li>
                    <li><i class="fa fa-check"></i> definition</li>
                    <li><i class="fa fa-check"></i> illustration</li>
                    <li><i class="fa fa-check"></i> related words </li>
                  </ul>
                </li>
                <li class='pricing-feature'>glossary catalog</li>
                <li class='pricing-feature'>A game of context </li>

              </ul>
              <a href="{!! lang_route('payment', ['id'=>$id, 'plan'=>'1', 'token'=>$token]) !!}" class='orangeBtn mb-4'>Choose plan</a>
            </div>
            <div class='pricing-item pricing__item--featured active'>
              <div class='pricing-deco'>

                <div class='pricing-price'><span class='pricing-currency'>$</span>9.99
                  <span class="pricing-period">/ month</span>
                </div>
                <h3 class='pricing-title'>Premium plan</h3>
              </div>
              <ul class='pricing-feature-list'>
                <li class='pricing-feature'>Reading assistant
                  <ul class="priceListing">
                    <li><i class="fa fa-check"></i> context</li>
                    <li><i class="fa fa-check"></i> keyword</li>
                    <li><i class="fa fa-check"></i> definition</li>
                    <li><i class="fa fa-check"></i> illustration</li>
                    <li><i class="fa fa-check"></i> related words</li>
                    <li><i class="fa fa-check"></i> export results</li>
                    <li><i class="fa fa-check"></i> file upload</li>
                  </ul>
                </li>
                <li class='pricing-feature'>glossary catalog</li>
                <li class='pricing-feature'>A game of context </li>
                <li class='pricing-feature'>Learning center </li>

              </ul>
              <a href="{!! lang_route('payment', ['id'=>$id, 'plan'=>'2', 'token'=>$token]) !!}" class='orangeBtn mb-4'>Choose plan</a>
            </div>
            <div class='pricing-item'>
              <div class='pricing-deco'>

                <div class='pricing-price'><span class='pricing-currency'>$</span>6.99
                  <span class="pricing-period">/ month</span>
                </div>
                <h3 class='pricing-title'>Advance plan</h3>
              </div>
              <ul class='pricing-feature-list'>
                <li class='pricing-feature'>Reading assistant
                  <ul class="priceListing">
                    <li><i class="fa fa-check"></i> context</li>
                    <li><i class="fa fa-check"></i> keyword</li>
                    <li><i class="fa fa-check"></i> definition</li>
                    <li><i class="fa fa-check"></i> illustration</li>
                    <li><i class="fa fa-check"></i> related words</li>
                    <li><i class="fa fa-check"></i> export results</li>
                  </ul>
                </li>
                <li class='pricing-feature'>glossary catalog</li>
                <li class='pricing-feature'>A game of context </li>

              </ul>
              <a href="{!! lang_route('payment', ['id'=>$id, 'plan'=>'3', 'token'=>$token]) !!}" class='orangeBtn mb-4'>Choose plan</a>
            </div>
          </div>

        </div>
      </div>

    </div>
  </div>
</div>
{!! HTML::script('assets/js/login.js') !!}
@endsection
