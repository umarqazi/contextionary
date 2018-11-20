$(document).ready(function() {
    $('#check_box_auto_renew').change(function() {

        $('.default-loader').css('display', 'block');

        if($(this).is(":checked")) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': token
                },
                type    : "POST",
                url     : autopay,
                data    : { id: +package_id},
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
                headers: {
                    'X-CSRF-TOKEN': token
                },
                type    :   "POST",
                url     :   cancelautopay,
                async   :   false,
            }).done(function( res ) {
                if(res == 1){
                    $('.default-loader').css('display', 'none');
                    toastr.success(message);
                }
            });
        }
    });
});