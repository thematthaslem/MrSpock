<?php

  function get_user_from_email($email)
  {
    $conn = db_connect();
    $data = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $data->bindparam(':email', $email);
    $data->execute();

    $arr = $data->fetchAll(PDO::FETCH_ASSOC);

    return $arr;
  }

  // If user is logged in AND the email is confirmed, return true
  function check_logged_in()
  {
    if( isset($_SESSION['user']) )
    {

      $userinfo = get_user_from_email($_SESSION['user']);
      if( $userinfo[0]['confirm_email'] == 'confirmed' )
      {
        return true;
      }

    }
    else
    {
      return false;
    }
  }


  function get_favorite($art_id)
  {
    $user = $_SESSION['user'];

    $conn = db_connect();
    $data = $conn->prepare("SELECT * FROM favorites WHERE user = :user AND art_id = :art_id");
    $data->bindparam(':user', $user);
    $data->bindparam(':art_id', $art_id);
    $data->execute();

    $arr = $data->fetchAll(PDO::FETCH_ASSOC);

    return $arr;
  }
?>
