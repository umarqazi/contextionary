$(document).ready(function () {

    new WOW().init();

    if ($('#datepicker-example-1')[0]) {
        $('#datepicker-example-1').datepicker({});
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

    $(".rightMenu").click(function () {
        event.stopPropagation();
    });

    $(".dropDown").click(function () {
        event.stopPropagation();
        $(this).find('.dropDown-block').fadeIn();
    });

    $(".dropDown-block").click(function () {
        event.stopPropagation();
    });

    //Mobile menu
    $('body').click(function () {
        $("aside").css('left', '-300px');
    });

    $(".mobileIcon").click(function () {
        event.stopPropagation();
        $("aside").css('left', '0');
    });

    $(".closeMenu").click(function () {
        $("aside").css('left', '-300px');
    });

    $("aside").click(function () {
        event.stopPropagation();
    });

});

//custom scroll bar

(function ($) {
    $(window).on("load", function () {
        $("aside").mCustomScrollbar();
    });
})(jQuery);