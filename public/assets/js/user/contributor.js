$(".form-container a.orangeBtn").click(function() {

    $(this).parents(".form-container:not(:last-of-type)").removeClass("active");
});

$("a.goto-fimiliar").click(function() {
    $(".select-fimiliar").addClass("active");
});

$("a.goto-lang").click(function() {
    if($('#checkBoxT').prop("checked") == false){
        $('#loader').removeClass('hidden');
        $('#init_cont_form').submit();
    }else{
        $(".select-lang").addClass("active");
    }
});

$("a.back-to").click(function() {
    $(this).parents(".form-container").removeClass("active");
    $(this).parents(".form-container").prev(".form-container").addClass("active");
});