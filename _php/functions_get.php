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

  function check_logged_in()
  {
    if( isset($_SESSION['user']) )
    {
      return true;
    }
    else
    {
      return false;
    }
  }
?>
