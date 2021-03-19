<?php
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
