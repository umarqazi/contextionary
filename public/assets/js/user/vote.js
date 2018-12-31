$(".voteMeaningBg .contextListing li").click(function () {
    var check=$('#rules').find('input[type=checkbox]:checked').length;
    $('.make-unchecked').prop( "checked", false );
    $(this).siblings().removeClass('active');
    $(this).addClass('active');
    var tab_id = $(this).attr('data-tab');
    $('.tab-pane').removeClass('current');
    $(this).addClass('current');
    $("#"+tab_id).addClass('current');
    $('#submit-button').addClass('grey');
    $('#submit-button').attr("disabled", true);
});
$(".illustrator-div .illustrators-active").click(function () {
    $('.make-unchecked').prop( "checked", false );
    var check=$('#rules').find('input[type=checkbox]:checked').length;
    $(this).siblings().removeClass('active-illustrator');
    $(this).addClass('active-illustrator');
    $('#submit-button').addClass('grey');
    $('#submit-button').attr("disabled", true);
});
$(".illustrator-div .fancybox").click(function () {
    $('.illustrator-checkbox').attr('checked', false);
    $(this).parent().next('.illustrator-checkbox').attr('checked', true);
});
$('.make-unchecked').click(function () {

    var check=$('#rules').find('input[type=checkbox]:checked').length;
    var radioCheck=$('#radio-check').find('input[type=radio]:checked').length;
    var illustrator=$('#illustrator-rules').find('input[type=checkbox]:checked').length;
    alert(radioCheck);
    alert(illustrator);
    if(radioCheck >= 1){
        if(check >= 4){
            $('#submit-button').removeClass('grey');
            $('#submit-button').removeAttr("disabled");
        }else if(illustrator >= 3){
            $('#submit-button').removeClass('grey');
            $('#submit-button').removeAttr("disabled", true);
        }
        else{
            $('#submit-button').addClass('grey');
            $('#submit-button').attr("disabled", true);
        }
    }

});