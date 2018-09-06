
$(window).on('resize orientationChange', function (event) {
       $('.slider').slick('reinit');
   });

//custom scroll bar

$(document).ready(function () {

    if ($('.contributorSlider')[0]) {
        $('.contributorSlider').slick({
            dots: true,
            infinite: true,
            speed: 500,
            slidesToShow: 1,
            adaptiveHeight: true
        });

    }
});