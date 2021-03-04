<?php
  require('connect.php');
  require('functions_get.php');

  // If not logged in, get out
  if( !isset($_SESSION['user']) )
  {
    header('Location: ../index.php');
  }

  $user = $_SESSION['user'];
  $oldpass = $_POST['oldpass'];
  $pass1 = $_POST['pass1'];
  $pass2 = $_POST['pass2'];

  // Secure password
  $securepass = password_hash($pass1, PASSWORD_DEFAULT);

  $error_arr = array();
  $error = false;

  $user_get = get_user_from_email($user);

  // Make sure passwords match
  if( $pass1 != $pass2 )
  {
    // If not add to error array
    $error_arr[] = "Passwords must match!";
  }

  // Check make sure old pass is correct
  if( !(password_verify($oldpass, $user_get[0]['pass'])) )
  {
    $error_arr[] = "The password you entered is wrong.";
  }

  // Update password
  if( empty($error_arr) )
  {
    $conn = db_connect();
    $data = $conn->prepare("UPDATE users SET pass = :pass WHERE email = :user");
    $data->bindparam(':pass', $securepass);
    $data->bindparam(':user', $user);

    if($data->execute())
    {
      $_SESSION['success'] = "Password updated successfully!";
    }
    else
    {
      $error_arr[] = "Password couldn't be updated for some reason...";
    }
  }

  // If there are error, set $_SESSION['error']
  if ( !empty($error_arr) )
  {
    $_SESSION['error'] = $error_arr;
  }

  header('Location: ../editinfo.php');


?>
