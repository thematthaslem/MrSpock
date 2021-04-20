<?php
  require('connect.php');
  require('votes.php');

  /*
  FOR VOTE BUTTONS
  - only allow voting if user is logged in
  - disable voting by adding class 'not-logged-in'
  */
  $notLoggedIn = "";
  if( !(isset($_SESSION['user'])) )
  {
    $notLoggedIn = "not-logged-in";
  }

  /*
  GET CLAIMS
  */

  $art_id = $_POST['art_id'];

  $conn = db_connect();

  $data = $conn->prepare('SELECT * FROM claims WHERE art_id = :art_id');
  $data->bindparam(':art_id', $art_id);

  $data->execute();

  $arr = $data->fetchAll(PDO::FETCH_ASSOC);

  $i = 1;
  foreach($arr as $row){
    $claim_id = $row['id'];
    $title = $row['title'];
    $author = $row['author'];
    $reproduce = $row['reproduce'];
    $source = $row['source'];
    $datasets = $row['datasets'];
    $results = $row['results'];

    /*
      Get all the votes for this claim
    */
    $upvotes = countUpvotes($claim_id);
    $downvotes = countDownvotes($claim_id);
    $totalVotes = $upvotes - $downvotes;

    // Get user's vote
    $upvoteSelected = "";
    $downvoteSelected = "";
    if( isset($_SESSION['user']) )
    {
      // $usersVote is either 'upvoted' or 'downvoted' or 'notvoted'.
      // if 'upvoted' $upvoteSelected = 'selected'
      // if 'downvoted' $downvoteSelected = 'selected'
      $usersVote = getUsersVote($claim_id);
      if($usersVote == 'upvoted'){$upvoteSelected = "selected";}
      if($usersVote == 'downvoted'){$downvoteSelected = "selected";}
    }

    echo "
      <div class=\"item-wrapper\">
        <div class=\"row title-upvote\">
          <div class=\"title\">#$i - $title</div>
          <div class=\"vote-buttons-wrap\" data-claim-id=\"$claim_id\" data-user=\"" . $_SESSION['user'] . "\">
            <div class=\"votes\">$totalVotes</div>
            <div class=\"vote-button upvote $upvoteSelected $notLoggedIn\" data-value=\"1\"><div class=\"count upvotes\">$upvotes</div></div>
            <div class=\"vote-button downvote $downvoteSelected $notLoggedIn\" data-value=\"-1\"><div class=\"count downvotes\">$downvotes</div></div>
          </div>
        </div>

        <div class=\"info\">By: <span class=\"value\">$author</span></div>
        <div class=\"info\">Can reproduce?: <span class=\"value\">$reproduce</span></div>
        <div class=\"info\">Source Code: <span class=\"value\">$source</span></div>
        <div class=\"info\">Datasets: <span class=\"value\">$datasets</span></div>
        <div class=\"info\">
          Results:
          <p>
            $results
          </p>
        </div>
      </div>
    ";
  }

  // If the array is empty, then there are no claims. State that
  if(empty($arr))
  {
    echo '<h2>There are no claims for this document.</h2>';
  }

?>
