<?php
  if(isset($_SESSION['user']))
  {
    $user = $_SESSION['user'];
  }


  /*
  Count votes
    - upvotes()
    - downvotes()

    -- Both returns count
  */
  function countUpvotes($id)
  {
    $conn = db_connect();
    $vote = 1;
    $query_votes = $conn->prepare('SELECT * FROM votes WHERE claim_id = :claim_id AND vote = :vote');
    $query_votes->bindparam(':claim_id', $id);
    $query_votes->bindparam(':vote', $vote);
    if($query_votes->execute())
    {
      $data = $query_votes->fetchAll(PDO::FETCH_ASSOC);
      return count($data);
    }
  }

  function countDownvotes($id)
  {
    $conn = db_connect();
    $vote = -1;
    $query_votes = $conn->prepare('SELECT * FROM votes WHERE claim_id = :claim_id AND vote = :vote');
    $query_votes->bindparam(':claim_id', $id);
    $query_votes->bindparam(':vote', $vote);
    if($query_votes->execute())
    {
      $data = $query_votes->fetchAll(PDO::FETCH_ASSOC);
      return count($data);
    }
  }

  /*
  Check what user's current vote is
    - return what that vote is
    - 'downvoted' if down vote
    - 'upvote' if upvoted
    - 'notvoted' if vote exists but is 0
    - 'nevervoted' if vote does not exist
  */
  function getUsersVote($id)
  {
    $conn = db_connect();
    $user = $_SESSION['user'];
    $query_votes = $conn->prepare('SELECT * FROM votes WHERE claim_id = :claim_id AND user = :user');
    $query_votes->bindparam(':claim_id', $id);
    $query_votes->bindparam(':user', $user);
    if($query_votes->execute())
    {
      $data = $query_votes->fetchAll(PDO::FETCH_ASSOC);

      $result = 'nevervoted';
      if( count($data) > 0 )
      {
        if( $data[0]['vote'] == 1 ){ $result = 'upvoted';}
        if( $data[0]['vote'] == -1 ){ $result = 'downvoted';}
        if( $data[0]['vote'] == 0 ){ $result = 'notvoted';}
      }

      return $result;

    }
  }



  /*
  Update vote
  */


?>
