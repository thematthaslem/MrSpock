// Content is dynamically loaded. Need to target document first.
var voteButton, voteWrap, voteCountWrap, currentVoteCount, currentSelected, totalCounter, currentTotalVote, currentTotalVoteCount;
$(document).on('click', '.vote-button', function() {
  voteButton = $(this);

  // If the voteButton has class 'not-logged-in' -> don't vote
  if(voteButton.hasClass('not-logged-in'))
  {
    return;
  }

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
