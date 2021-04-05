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
    // send email
    // E-mail contains a link that links to 'confirm_email' with GET values for token (token -> confirm_email token)
    $msg = 'Click the link to reset your password:
    <a href="http://localhost/_school/cs418/git/haslem-project/resetpass.php?token=' . $token . '">' . $token . '</a>';
    $subject = "Mr. Spock Account confirmation";
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: Mr. Spock";
    // If confirmation e-mail is sent
    if(mail($email,$subject,$msg, $headers))
    {

      $_SESSION['success'] = "An email has been sent to " . $email . " with a link to use to reset your password.";
      /*
        NOTE: Don't need to sign them in automattically since they haven't confirmed their email yet

      $_SESSION['user'] = $email;
      */
      header('Location: ../successpage.php');


    }
    else
    {
      $error_arr[] = "Sorry, Captain. An error occured with sending an e-mail.";
    }
    /*
    echo '
    <h2>What should be emailed: </h2>

    Click the link to reset your password:
    <a href="../resetpass.php?token=' . $token . '">' . $token . '</a>
    ';*/
  }

?>
