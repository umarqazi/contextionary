$(document).ready(function() {
    $('#check_box_auto_renew').change(function() {
        $('.default-loader').css('display', 'block');
        if($(this).is(":checked")) {
            $.ajax({
                type    : "POST",
                url     : "{!! lang_url('autopay') !!}",
                data    : { id:{{$activePlan->package_id}}, _token: '{{csrf_token()}}'},
            async   : false,
        }).done(function( res ) {
                if(res == 1){
                    $('.default-loader').css('display', 'none');
                    toastr.success("{{ t('Auto Renewal Active') }}");
                }
            });
        }
        else {
            $.ajax({
                type    :   "POST",
                url     :   "{!! lang_url('cancelautopay') !!}",
                data    :   { _token: '{{csrf_token()}}'},
                async   :   false,
            }).done(function( res ) {
                if(res == 1){
                    $('.default-loader').css('display', 'none');
                    toastr.success("{{ t('Auto Renewal Disabled') }}");
                }
            });
        }
    });
});