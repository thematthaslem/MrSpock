$(document).ready(function(){// Open advanced-search-items wrap on click of .open-advanced
$('.open-advanced').on('click',
  function(e)
  {
    $(this).parent().next('.advanced-search-items').toggleClass('open');
  }
);
var backButton = $('.go-back-button');
backButton.on('click', function(){
  window.history.back();
});
console.log('Hi Professor!');
var newClaimForm = $('#new-claim');

/*
  When a reproduce button is clicked.
    - Set that button to active
    - Unset other active button
    - Set hidden field with value of button
*/
var reproduceButton = $('.reproduce-button');
var reproduceInput = newClaimForm.find('[name=reproduce]');
reproduceButton.on('click', function(){
  $('.reproduce-button.active').removeClass('active');
  $(this).addClass('active');
  reproduceInput.val($(this).text());
});
});              