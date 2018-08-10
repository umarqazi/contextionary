
$(document).ready(function () {

    //Product detail slider
    var owl = $("#owl-demo");

    owl.owlCarousel({

        items: 1, //10 items above 1000px browser width
        itemsDesktop: [1000, 1], //5 items between 1000px and 901px
        itemsDesktopSmall: [900, 1], // 3 items betweem 900px and 601px
        itemsTablet: [600, 1], //2 items between 600 and 0;
        itemsTablet: [480, 1], //2 items between 500 and 0;
        dots: true,

    });

    // Custom Navigation Events
    $(".next").click(function () {
        owl.trigger('owl.next');
    })
    $(".prev").click(function () {
        owl.trigger('owl.prev');
    });

});
