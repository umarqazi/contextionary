//Show translate div according to checkboxes
$('#checkBox2').click(function(){
    if($(this).prop("checked") === true){
        $('#desired-roles-translate').removeClass('hidden');
    }
    else{
        $('#desired-roles-translate').addClass('hidden');
        $('input[name="language"]').prop("checked", false);
    }
});