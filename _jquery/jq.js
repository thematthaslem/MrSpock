$(document).ready(function(){// Open advanced-search-items wrap on click of .open-advanced
$('.open-advanced').on('click',
  function(e)
  {
    $(this).parent().next('.advanced-search-items').toggleClass('open');
  }
);

// Closes if clicked somewhere else
$('body').click(function(evt){
       if($(evt.target).hasClass('advanced-search-items') || $(evt.target).hasClass('open-advanced') || $(evt.target).hasClass('open-advanced-link'))
       {
         return;
       }

       //For descendants of menu_content being clicked, remove this check if you do not want to put constraint on descendants.
       if($(evt.target).closest('.advanced-search-items').length)
          return;

      //Do processing of click event here for every element except with id menu_content
      $('.advanced-search-items').removeClass('open');
});
$("a.button.dropdown").on('click', function(e){
  var targetClass = $(this).attr('data-target');
  $('.' + targetClass).toggleClass('open');
  $(this).toggleClass('open');
  console.log(targetClass);
});
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

/*
  When form is submitted
*/
var newClaimForm = $('form#new-claim');
var successWrap = $('.new-claim-wrapper > .message.success');
var failWrap = $('.new-claim-wrapper > .message.fail');
newClaimForm.on('submit', function(e)
{
  // Stop the form from sending
  e.preventDefault();

  // Gather data
  postData = $(this).serialize();

  $.ajax(
    {
      url: "_php/send_claim_action.php",
      type: 'POST',
      data: postData,
    }
  ).done(
    function(results){
      // Hide both messages
      successWrap.removeClass('show');
      failWrap.removeClass('show');

      if(results === "success"){
        // Show success
        successWrap.addClass('show');
        refreshClaims();
      }
      else{
        // Show fail
        failWrap.addClass('show');
      }
    });

});


/*
  Refresh Claims function
*/
refreshClaims();
var claimsItemsWrapper = $('.claim-items-wrapper');

function refreshClaims()
{
  var artId = $('input#art_id').val();
  $.ajax(
    {
      url: "_php/show_claims.php",
      type: 'POST',
      data: {'art_id': artId}
    }
  ).done(
    function(results){
      claimsItemsWrapper.html(results);
    });
}

$('#refresh').on('click', function(){
  refreshClaims();
  console.log('refresh');
});
});                 