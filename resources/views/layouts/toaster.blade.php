

  @if(Session::has('message'))
  <div class="alert alert-{!! Session::get('alert_type') !!}">
      {{ Session::get('message') }}
  </div>
  @endif
