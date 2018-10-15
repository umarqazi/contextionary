$(".toggle-password").click(function() {

  $(this).toggleClass("fa-eye fa-eye-slash");
  var input = $($(this).attr("toggle"));
  if (input.attr("type") == "password") {
    input.attr("type", "text");
  } else {
    input.attr("type", "password");
  }
});
$(".toggle-password2").click(function() {

  $(this).toggleClass("fa-eye fa-eye-slash");
  var input = $($(this).attr("toggle"));
  if (input.attr("type") == "password") {
    input.attr("type", "text");
  } else {
    input.attr("type", "password");
  }
});
function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
      $('#profile-img-tag').attr('src', e.target.result);
    }
    reader.readAsDataURL(input.files[0]);
  }
}
function showButtons(){
  $('.show-bid').hide();
  $('.hide_form').show();
}
$("#profile-img").change(function(){
  readURL(this);
});
$(".pricing-palden .pricing-item").hover(function () {
  $(this).siblings().removeClass('active');
  $(this).addClass('active');
});
$(".pricing-palden .pricing-item").mouseleave(function () {
  $('.pricing-palden .pricing-item').siblings().removeClass('active');
  $('.pricing-item.pricing__item--featured').addClass('active');
});

$("#meaning-area").keyup(function(){
  $("#count").text("Characters: " + ($(this).val().length)+" / 2500");
});

function restrictBid(bid){
  var coins=bid.value;
  if(parseInt(user_coins) < parseInt(coins)){
    $('#bid-button').addClass('grey');
    $('#bid-button').attr("disabled", true);
  }else{
    $('#bid-button').removeClass('grey');
    $('#bid-button').removeAttr("disabled", true);
  }
  if(coins < 1){
    $('#bid-button').addClass('grey');
    $('#bid-button').attr("disabled", true);
  }
}