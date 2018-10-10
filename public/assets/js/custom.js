$(document).ready(function () {
    $(".coins").val(1);
    new WOW().init();
    var today = new Date();
    var dd = today.getDate();
    var mm = '0' + (today.getMonth()+1); //January is 0!
    var yyyy = today.getFullYear()-10; // change according to year 0 for current
    var today1 = mm + '/' + dd + '/' + yyyy;
    if ($('#datepicker-example-1')[0]) {
        $('#datepicker-example-1').datepicker({endDate: today});
    }

    $("body").click(function () {
        $("header .mainMenu").animate({
            left: '-250px'
        });
    });

    $("#menu").click(function () {
        event.stopPropagation();
        $("header .mainMenu").animate({
            left: '0px'
        });

        $("header .mainMenu").click(function () {
            event.stopPropagation();
        });
    });

    $(".closeMenu").click(function () {
        $("header .mainMenu").animate({
            left: '-250px'
        });
    });

    $(".languageBar").click(function () {
        $(this).find(".list").slideToggle();
        $(".list li").click(function () {
            var get_value = $(this).html();
            var get_parent = $(this).parent().parent().get(0);
            $(get_parent).find('.active').html('').append(get_value + "<i class='fa fa-chevron-down'></i>");
        });
    });

    //Dashboard menu
    $(".menuIcon").click(function () {

        $("aside").toggleClass('compressMenu');
        $(".dashBoard-container").toggleClass('dashboardLrg');
        $('.contributorSlider')[0].slick.refresh();

    });

    //Height script
    var get_height = $(".dashBoard-container").innerHeight();
    $("aside").css('height', get_height);

    $(window).scroll(function () {
        var get_height = $(".dashBoard-container").innerHeight();
        $("aside").css('height', get_height);
    });

    //Right Dropdown
    $('body').click(function () {
        $(".rightMenu .rightDropdown").fadeOut();
        $('.dropDown-block').fadeOut();
    });

    $(".rightMenu").click(function () {
        $(".rightMenu .rightDropdown").fadeIn();
    });

    $(".rightMenu").click(function (event) {
        event.stopPropagation();
    });

    $(".dropDown").click(function (event) {
        event.stopPropagation();
        $(this).find('.dropDown-block').fadeIn();
    });

    $(".dropDown-block").click(function (event) {
        event.stopPropagation();
    });

    //Mobile menu
    $('body').click(function () {
        $("aside").css('left', '-300px');
    });

    $(".mobileIcon").click(function (event) {
        event.stopPropagation();
        $("aside").css('left', '0');
    });

    $(".closeMenu").click(function () {
        $("aside").css('left', '-300px');
    });

    $("aside").click(function () {
        event.stopPropagation();
    });

    $(".exploreSection ul li").click(function () {
        $(this).siblings().removeClass('active');
        $(this).addClass('active');
    });

    //plan effect
    $(".pricing-palden .pricing-item").hover(function () {
        $(this).siblings().removeClass('active');
        $(this).addClass('active');
    });

    $(".pricing-palden .pricing-item").mouseleave(function () {
        $('.pricing-palden .pricing-item').siblings().removeClass('active');
        $('.pricing-item.pricing__item--featured').addClass('active');
    });

    //Add minus coins
    $('.add').click(function () {
        var coins=$(".coins").val();
        if(Number(coins) < Number(user_coins)){
            $(".coins").val(+coins + 1);
        }else{
            $(".coins").val(user_coins);
        }
    });

    $('.sub').click(function () {
        var coins=$(".coins").val();
        if(coins > 0){
            $(".coins").val(+coins - 1);
        }
    });

    $(".enter-phrase").keypress(function () {
        $(".bidBtn").removeClass('disabled');
    });
    $('#form-submission').submit(function() {
        $('.default-loader').css('display', 'block');
    });
    $('#bid-submission').submit(function() {
        $('.default-loader').css('display', 'block');
    });

    $(".comment-icon").click(function () {
        var get_parent = $(".comment-icon").parent().get(0);
        $(get_parent).toggleClass('open');
    });
});
(function ($) {
    $(window).on("load", function () {
        $("aside").mCustomScrollbar();
    });
})(jQuery);

