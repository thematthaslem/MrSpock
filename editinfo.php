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
  <div class="all-content edit-info">
    <div class="left-bar">
      <div class="logo-wrap"> <img src="_pics/logo.png" alt="Mr. Spock Logo"/></div>
      <div class="nav-wrap">
        <div class="nav-links"><a href="index.php">Home </a><a href="_php/logout.php">Logout </a></div>
      </div>
    </div>
    <div class="content-wrap">
      <div class="messages-wrap"><?php
  // If there are errors in the session
  // -> Print errors in a box
  // -> unset session
  if( isset($_SESSION['error']) )
  {
    $err_arr = $_SESSION['error'];

    if( isset($_SESSION['email']) )
    {
      $email = $_SESSION['email'];
    }

?>
  <div class="error-wrap">
    <?php
      foreach ($err_arr as $key => $value) {
        echo "
          <p class=\"error\">
            ". $value . "
          </p>
        ";
      }
     ?>
  </div>

<?php
    // Unset the SESSION
    unset($_SESSION['error']);
  }
?>
<?php
  // If there are success message in the session
  // -> Print message in a box
  if( isset($_SESSION['success']) )
  {
    $succ_msg = $_SESSION['success'];

?>
  <div class="success-wrap">
    <?php echo $succ_msg; ?>
  </div>

<?php
    // Unset the SESSION
    unset($_SESSION['success']);
  }
  /*
  else {
    header('Location: index.php');
  }
  */
?>

      </div>
      <h1>personal information </h1><br/>
      <div class="form-wrap"><?php
  include('_php/connect.php');
  include('_php/functions_get.php');

  $user = $_SESSION['user'];
  $userinfo = get_user_from_email($user);

  $email = $userinfo[0]['email'];
  $fname = $userinfo[0]['fname'];
  $lname = $userinfo[0]['lname'];

?>


<form method="POST" action="_php/editinfo_action.php" class="column">
  <input type="email" name="email" placeholder="e-mail" <?php echo 'value="' . $email . '"'; ?>/>
  <input type="text" name="fname" placeholder="first name" <?php echo 'value="' . $fname . '"'; ?>/>
  <input type="text" name="lname" placeholder="last name" <?php echo 'value="' . $lname . '"'; ?>/>

  <button type="submit" class="dark">Save Changes</button>
</form>

        <!--form.column(method="POST" action="editinfo_action.php")
        input(type="text" name="fname" placeholder="first name")
        input(type="text" name="lname" placeholder="last name")
        input(type="text" name="email" placeholder="e-mail")
        button.dark(type="submit")
          | Save Changes
        -->
      </div><br/>
      <h1>change password</h1>
      <div class="form-wrap">
        <form method="POST" action="_php/changepass_action.php" class="column">
          <input type="password" name="oldpass" placeholder="old password" required="required"/>
          <input type="password" name="pass1" placeholder="new password" required="required"/>
          <input type="password" name="pass2" placeholder="re-enter new password" required="required"/>
          <button type="submit" class="dark">Save Changes</button>
        </form>
      </div>
    </div>
  </div>
</body>