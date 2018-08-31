@include('layouts.body')
<script type="text/javascript">
    var expireDate="<?php echo  date('M d Y h:i:s'); ?>";
</script>
@include('layouts.sidebar')
<section class="dashBoard-container summaryBg">
  @include('layouts.main_header')
  @yield('content')
</section>
@include('layouts.body_footer')
