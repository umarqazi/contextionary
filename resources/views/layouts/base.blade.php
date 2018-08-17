@include('layouts.body')
  <header>
    @include('layouts.header')
  </header>
  <section class="mainPageContainer">
    @yield('content')
    <footer>
      @include('layouts.footer')
    </footer>
  </section>
@include('layouts.body_footer')
