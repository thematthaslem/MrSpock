<?php
  /*if(!isset($_SESSION))
  {
    session_start();
  }*/
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<html></html>
<head>
  <title>Mr. Spock</title>
  <link rel="stylesheet" type="text/css" href="main.css"/>
  <script src="_jquery/jquery-3.3.1.min.js"></script>
  <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <script src="_jquery/jq.js"></script>
</head>
<body>
  <div class="all-content login">
    <div class="content-wrap"> 
      <div class="logo-wrap"> <a href="index.php"> <img src="_pics/logo.png" alt="Mr. Spock Logo"/></a></div>
      <div class="success-wrap">
        <p class="success"><?php
require('_php/connect.php');

// Make sure there's a token
if( isset($_GET['token']) )
{
  $token = $_GET['token'];
}
else
{
  //header('Location: index.php');
}

// Find user in database and confirm the email (set 'confirm_email' column to 'confirmed')
$conn = db_connect();
$data = $conn->prepare("UPDATE users SET confirm_email = :confirmed WHERE confirm_email = :token");
$confirm = "confirmed";
$data->bindparam(':confirmed', $confirm);
$data->bindparam(':token', $token);

if($data->execute())
{
  echo 'Thank you for confirming your e-mail!';
}
else
{
  echo 'We are sorry. Something went wrong with confirming your e-mail.';
}

?>

        </p>
      </div><a href="login.php" class="button light">Login</a>
    </div>
  </div>
</body>