<?php
  require('connect.php');
  require('functions_get.php');

  $email = $_POST['email'];
  $pass1 = $_POST['pass1'];
  $pass2 = $_POST['pass2'];

  $error_arr = array();
  $error = false;
  // Make sure passwords match
  if( $pass1 != $pass2 )
  {
    // If not add to error array
    $error_arr[] = "Passwords must match!";
  }

  // Check if username already exists
  $prevUser = get_user_from_email($email);
  if(!empty($prevUser))
  {
    $error_arr[] = "That email is already associated with another user.";
  }


  // IF EVERYTHING IS ALL GOOD
  if ( empty($error_arr) )
  {
    // send email
    $msg = "Thanks for signing up to Mr. Spock!";
    $subject = "Mr. Spock Account confirmation";

    mail($email,$subject,$msg);

    // Secure password
    $securepass = password_hash($pass1, PASSWORD_DEFAULT);

    // Update database
    $conn = db_connect();
    $data = $conn->prepare("INSERT INTO users (email, pass) VALUES (:email, :pass)");
    $data->bindparam(':email', $email);
    $data->bindparam(':pass', $securepass);

    if($data->execute())
    {
      $_SESSION['success'] = "Direct hit, Captain. Your account is setup. An email has been sent to " . $email . " to verify.";
      $_SESSION['user'] = $email;
      header('Location: ../successpage.php');
    }
    else
    {
      $error_arr[] = "Sorry, Captain. An error occured in our databases.";
    }

  }


  // If the error_array is not empty, set the SESSION
  if ( !empty($error_arr) )
  {
    $_SESSION['error'] = $error_arr;
    $_SESSION['email'] = $email;
    header('Location: ../register.php');
  }




?>
