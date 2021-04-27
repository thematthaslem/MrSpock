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
function startDictation() {

    if (window.hasOwnProperty('webkitSpeechRecognition')) {

      var recognition = new webkitSpeechRecognition();

      recognition.continuous = false;
      recognition.interimResults = false;

      recognition.lang = "en-US";
      recognition.start();

      recognition.onresult = function(e) {
        document.getElementById('search_input').value
                                 = e.results[0][0].transcript;
       $('.mic-wrap').removeClass('active');
        recognition.stop();

        document.getElementById('search-form').submit();

      };

      recognition.onerror = function(e) {
        recognition.stop();
        $('.mic-wrap').removeClass('active');
      }

    }
  }


$('#microphone').on('click', function(e){
  startDictation();
  $('.mic-wrap').addClass('active');
  setTimeout(function(){ $('.mic-wrap').removeClass('active'); }, 6000);
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
// Content is dynamically loaded. Need to target document first.
var voteButton, voteWrap, voteCountWrap, currentVoteCount, currentSelected, totalCounter, currentTotalVote, currentTotalVoteCount;
$(document).on('click', '.vote-button', function() {
  voteButton = $(this);
  voteWrap = voteButton.closest('.vote-buttons-wrap');
  voteCountWrap = voteButton.find('.count');
  currentVoteCount = parseInt(voteCountWrap.text());
  currentTotalVoteWrap = voteWrap.find('.votes');
  currentTotalVoteCount = parseInt(currentTotalVoteWrap.text());
  var vote = voteButton.attr('data-value');
  var voteInt = parseInt(vote);
  var claimId = voteWrap.attr('data-claim-id');
  var user = voteWrap.attr('data-user');

  var alreadySelected = false;
  var currentVoteAdjuster = 1; // This will raise counter for this button if 1, lower it if -1


  /*
    Check if the current vote button is already selected.
      - If selected:
        |-> Lower the counter instead of increasing it
        |-> Adjust totalVoteCount Accordingly
        |-> deselect vote button
        |-> Update database with a '0'
  */
  if (voteButton.hasClass('selected'))
  {
    alreadySelected = true;
    vote = 0;
    currentVoteAdjuster = -1;
  }


  $.ajax(
    {
      url: '_php/update_vote.php',
      type: 'post',
      data:
      {
        vote: vote,
        claim_id: claimId,
        user: user
      }
    }
  ).done(
    function(results)
    {
      //console.log(results + currentVoteCount);
      console.log("results: " + results);
      console.log("currentTotalVote: " + currentTotalVoteCount);



      // Update buttons
      currentSelected = voteWrap.find('.selected');
      // - update counter for whatever selected
      voteCountWrap.text(currentVoteCount+currentVoteAdjuster);
      // - update counter for the other one.
      //    -- only if they're clicking on the button that isn't selected
      if(!alreadySelected)
      {
        currentSelected.find('.count').text(parseInt(currentSelected.find('.count').text()) - 1);
      }


      // - update total counter
      var upvotes = parseInt( $('.count.upvotes').text() );
      var downvotes = parseInt( $('.count.downvotes').text() );

      //currentTotalVoteWrap.text(currentTotalVoteCount + voteInt);
      currentTotalVoteWrap.text(upvotes - downvotes);


      // - remove 'selected' from button
      voteWrap.find('.selected').removeClass('selected');

      // - add selected
      // if it's already selected, just leave it removed
      if(!alreadySelected)
      {
        voteButton.addClass('selected');
      }




    }
  )
});
});                         