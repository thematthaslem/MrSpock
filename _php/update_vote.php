<?php
  require('connect.php');
  require('votes.php');

  $vote = $_POST['vote'];
  $user = $_POST['user'];
  $claim_id = $_POST['claim_id'];

  $conn = db_connect();

  /*
    SEE IF USER HAS VOTED ALREADY
  */
  $usersVote = getUsersVote($claim_id);

  // - If they have never voted
  if( $usersVote == 'nevervoted' )
  {
    $query = $conn->prepare('INSERT INTO votes (user, claim_id, vote) VALUES (:user, :claim_id, :vote)');
    $query->bindparam(':user', $user);
    $query->bindparam(':claim_id', $claim_id);
    $query->bindparam(':vote', $vote);

    if($query->execute())
    {
      $results = "inserted";
    }
    else
    {
      $results = "something went wrong";
    }
  }
  else
  {
    $query = $conn->prepare('UPDATE votes SET vote = :vote WHERE claim_id = :claim_id AND user = :user');
    $query->bindparam(':user', $user);
    $query->bindparam(':claim_id', $claim_id);
    $query->bindparam(':vote', $vote);
    if($query->execute())
    {
      $results = "updated";
    }
    else
    {
      $results = "something went wrong";
    }
  }

  echo $results;


?>
