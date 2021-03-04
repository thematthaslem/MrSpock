<?php
  require('connect.php');
  require('functions_get.php');

  $email = $_POST['email'];

  // make sure email exists
  $user = get_user_from_email($email);

  $err_arr = array();

  // If email doesn't exist
  if( empty($user) )
  {
      $err_arr[] = "That email doesn't exist in our system.";
      $_SESSION['error'] = $err_arr;
      header('Location: ../forgotpass.php');
  }

  // set reset token
  $token = md5(uniqid(rand(), true));

  // save token in user's database
  $conn = db_connect();
  $data = $conn->prepare("UPDATE users SET reset_pass_token = :token WHERE email = :email");
  $data->bindparam(':token', $token);
  $data->bindparam(':email', $email);

  if ($data->execute())
  {
    echo '
    <h2>What should be emailed: </h2>

    Click the link to reset your password:
    <a href="../resetpass.php?token=' . $token . '">' . $token . '</a>
    ';
  }

?>
