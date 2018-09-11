$(".voteMeaningBg .contextListing li").click(function () {
    $(this).siblings().removeClass('active');
    $(this).addClass('active');
    var tab_id = $(this).attr('data-tab');
    console.log(tab_id);
    $('.tab-pane').removeClass('current');
    $(this).addClass('current');
    $("#"+tab_id).addClass('current');
});