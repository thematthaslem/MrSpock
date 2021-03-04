<?php
  require('connect.php');

  // If not logged in, get out
  if( !isset($_SESSION['user']) )
  {
    header('Location: ../index.php');
  }

  $user = $_SESSION['user'];
  $fname = $_POST['fname'];
  $lname = $_POST['lname'];
  $email = $_POST['email'];

  $conn = db_connect();
  $data = $conn->prepare("UPDATE users SET email = :email, fname = :fname, lname = :lname WHERE email = :user");
  $data->bindparam(':email', $email);
  $data->bindparam(':fname', $fname);
  $data->bindparam(':lname', $lname);
  $data->bindparam(':user', $user);

  if( $data->execute() )
  {
    // Change $_SESSION['user'] to match new email
    session_unset($_SESSION['user']);
    $_SESSION['user'] = $email;
    $_SESSION['success'] = "Information successfully changed.";
  }
  else
  {
    $_SESSION['error'] = "Sorry, something went wrong";
  }

  header('Location: ../editinfo.php');


?>
