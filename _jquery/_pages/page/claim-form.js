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
