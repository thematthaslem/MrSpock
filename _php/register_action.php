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

    // Secure password
    $securepass = password_hash($pass1, PASSWORD_DEFAULT);

    // Generate token for email verification
    $token = md5(uniqid(rand(), true));

    // Update database
    $conn = db_connect();
    $data = $conn->prepare("INSERT INTO users (email, pass, confirm_email) VALUES (:email, :pass, :token)");
    $data->bindparam(':email', $email);
    $data->bindparam(':pass', $securepass);
    $data->bindparam(':token', $token);

    if($data->execute())
    {


      // send email
      // E-mail contains a link that links to 'confirm_email' with GET values for token (token -> confirm_email token)
      $msg = "Thanks for signing up to Mr. Spock! <a href=\"http://localhost/_school/cs418/git/haslem-project/confirm_email.php?token=$token\">Click here to verify e-mail</a>";
      $subject = "Mr. Spock Account confirmation";
      $headers = "MIME-Version: 1.0" . "\r\n";
      $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
      $headers .= "From: Mr. Spock";
      // If confirmation e-mail is sent
      if(mail($email,$subject,$msg, $headers))
      {

        $_SESSION['success'] = "Direct hit, Captain. Before we get started, you need to verify your e-mail.
        An email has been sent to " . $email . " to verify.";
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
