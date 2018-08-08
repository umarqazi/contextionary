@include('layouts.base_header')
  <header>
    @include('layouts.header')
  </header>
  <section class="mainPageContainer">
    @yield('content')
    <footer>
      @include('layouts.footer')
    </footer>
  </section>
@include('layouts.base_footer')
