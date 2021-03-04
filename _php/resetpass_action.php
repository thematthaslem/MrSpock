<?php
  require('connect.php');

  $token = $_POST['token'];
  $pass1 = $_POST['pass1'];
  $pass2 = $_POST['pass2'];

  $err_arr = array();

  // Make sure passwords match
  if( $pass1 != $pass2 )
  {
    $err_arr[] = "Those passwords don't match, sir!";
    $_SESSION['error'] = $err_arr;
    header('Location: ../resetpass.php?token=' . $token);
    die('whoops');
  }

  // secure password
  $securepass = password_hash($pass1, PASSWORD_DEFAULT);

  // If it's all good
  // -> update user where token is
  $conn = db_connect();
  $data = $conn->prepare("UPDATE users SET pass = :pass WHERE reset_pass_token = :token");
  $data->bindparam(':pass', $securepass);
  $data->bindparam(':token', $token);

  if($data->execute())
  {
    // Login and send them to editinfo page
    $data2 = $conn->prepare("SELECT * FROM users WHERE reset_pass_token = :token");
    $data2->bindparam(':token', $token);
    $data2->execute();
    $arr = $data2->fetchAll(PDO::FETCH_ASSOC);

    $_SESSION['user'] = $arr[0]['email'];
    $_SESSION['success'] = "Password has been reset successfully!";

    header('Location: ../editinfo.php');
  }

?>
