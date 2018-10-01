//light box
if ($('.fancybox')[0]) {
    $('.fancybox').fancybox();
    $(".fancybox-effects-a").fancybox({
        helpers: {
            title: {
                type: 'outside'
            },
            overlay: {
                speedOut: 0
            }
        }
    })
}

//Pdf View
if ($(".fancybox")[0]) {
    $(".fancybox")
        .attr('rel', 'gallery')
        .fancybox({
            padding: 0
        });
}

$(".gallerypdf").fancybox({
    openEffect: 'elastic',
    closeEffect: 'elastic',
    autoSize: true,
    type: 'iframe',
    iframe: {
        preload: false // fixes issue with iframe and IE
    }
});