<?php
  require('_php/connect.php');
  require('_php/functions_get.php');
/* TEST MAIL FUNCTION */
$toemail = "matthaslemschool@gmail.com";
//$toemail = " ";

$subject = "Test Email";

$body = "Hi, This is test email send by PHP Script <a href=\"http://localhost/_school/cs418/project/successpage.php\">click here</a>";

$headers = "From: sender\'s email";
$headers .= "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";



if (mail($toemail, $subject, $body, $headers)) {

    echo "Email successfully sent to $toemail..";

} else {

    echo "Email sending failed...";

}

/* Test registration success


$_SESSION['success'] = 'Your account is set-up! An email has been sent to thematthaslem@gmail.com.';
//<br />
//If you don\'t see it you can <a href="#">Resend E-mail</a>';
//header('Location: successpage.php');

$user = get_user_from_email('thematthaslem@gmail.com');
print_r($user);

echo '<br/><br/><h1>USER ID: ' . $user[0]['id'] . '</h1>';

*/
