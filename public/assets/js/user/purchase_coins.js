//Select coin
$(".coin-block").click(function () {
    $(".coin-block").removeClass("active");
    $(this).addClass("active");
    $('#show-purchase').removeClass('hide-button');
});