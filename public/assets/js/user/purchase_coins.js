//Select coin
$(".coin-block").click(function () {
    $(".coin-block").removeClass("active");
    $(this).addClass("active");
    $('#show-purchase').removeClass('hide-button');
});

function getCoins(){
    var role=$(".role option:selected").val();
    var points=$('#'+role).text();
    if(role!='' && points !=0){
        $('#role_points').val(points);
        $('#request-redeem').removeClass('grey');
        $('#request-redeem').removeAttr("disabled");
        $("#role_points").attr({
            "max" : points,        // substitute your own
            "min" : 1          // values (or variables) here
        });
    }else{
        $('#role_points').val('');
        $('#request-redeem').addClass('grey');
        $('#request-redeem').attr("disabled", true);
    }
}

$( "#form-submission" ).submit(function( event ) {
    $('#request-redeem').addClass('grey');
    $('#request-redeem').attr("disabled", true);
});