{!! HTML::script(asset('assets/js/toaster.js')) !!}
{!! HTML::style('//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css') !!}
<script>
    @if(Session::has('message'))
        toastr.{!! Session::get('alert_type') !!}("{{ Session::get('message') }}");
    @endif
</script>
