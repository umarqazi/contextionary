@include('layouts.base_header')
  <script type="text/javascript">
    var expireDate="<?php echo  date('M d Y h:i:s', Auth::user()->expiry_date); ?>";
    </script>
    @include('layouts.sidebar')
    <section class="dashBoard-container">
      @include('layouts.main_header')
      @yield('content')
    </section>
@include('layouts.base_footer')
