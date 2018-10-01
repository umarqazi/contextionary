$(".voteMeaningBg .contextListing li").click(function () {
    $('.make-unchecked').prop( "checked", false );
    $(this).siblings().removeClass('active');
    $(this).addClass('active');
    var tab_id = $(this).attr('data-tab');
    $('.tab-pane').removeClass('current');
    $(this).addClass('current');
    $("#"+tab_id).addClass('current');
    $('#submit-button').removeClass('grey');
    $('#submit-button').removeAttr("disabled");
});
$(".illustrator-div .illustrators-active").click(function () {
    $('.make-unchecked').prop( "checked", false );
    $(this).siblings().removeClass('active-illustrator');
    $(this).addClass('active-illustrator');
    $('#submit-button').removeClass('grey');
    $('#submit-button').removeAttr("disabled");
});