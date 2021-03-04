<?php
  require('connect.php');
  require('functions_get.php');

  $email = $_POST['email'];
  $pass = $_POST['pass'];
  //$pass = password_hash($pass, PASSWORD_DEFAULT);

  $err_arr = array();

  // Check if email is actually in there
  $user_get = get_user_from_email($email);
  print_r($user_get);

  if( empty($user_get) )
  {
    $err_arr[] = "There is no user with that e-mail.";
  }

  // If there is a user, Check if password matches
  else
  {
    if( !(password_verify($pass, $user_get[0]['pass'])) )
    {
      $err_arr[] = "The password you entered is wrong.";
    }
  }

  // If there are errors
  if( !empty($err_arr) )
  {
    $_SESSION['error'] = $err_arr;
    $_SESSION['email'] = $email;
    header('Location: ../login.php');
  }

  // If there are no errors -> set $_SESSION['user'];
  else
  {
    $_SESSION['user'] = $email;
    header('Location: ../index.php');
  }

?>
