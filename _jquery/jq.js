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
/*
  ADD/REMOVE FAVORITES TO DATABASE
*/

var favButton;
var art_id, user, art_title, art_date, art_author;
var fav_type; // 'add' or 'remove'
$('.favorite-button').on('click', function(e){
  favButton = $(this);

  art_id = $(this).attr('data-id');
  user = $(this).attr('data-user');
  art_title = $(this).attr('data-title');
  art_date = $(this).attr('data-date');
  art_author = $(this).attr('data-author');

  // If it already has class 'selected' or has data-type set to 'remove' -> that means we want to remove
  if($(this).hasClass('selected'))
  {
    fav_type = 'remove';
  }
  else
  {
    fav_type = 'add';
  }

  // Send ajax request
  $.ajax(
    {
      url: "_php/update_favorites.php",
      type: 'POST',
      data: {
        art_id: art_id,
        user: user,
        art_title: art_title,
        art_date: art_date,
        art_author: art_author,
        fav_type: fav_type
      }
    }
  ).done(
    function(results)
    {
      // Change the selected status of the button
      if(results == "success")
      {
        favButton.toggleClass('selected');

        // If the button has class 'remove-item' -> Remove the whole element it's in
        // - this is for the favorites_list.php which only has the remove button
        if( favButton.hasClass('remove-item') )
        {
          var itemWrapper = favButton.closest('.item');
          itemWrapper.remove();
        }

        // If the button has class 'page-button' -> Change text in button
        // - this is for the page.php. it goes from "Add to favorites" to "Remove from favorites"
        if( favButton.hasClass('page-button') )
        {
          var itemText = favButton.find('span');

          if( favButton.hasClass('selected') )
          {
            itemText.text('Remove From Favorites');
          }
          else
          {
            itemText.text('Add to Favorites');
          }

        }

      }
      else
      {
        alert('There was a problem with favoriting the item.');
      }



    }
  );

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