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
  <title>The Matt Haslem</title>
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
      <div class="logo-wrap"><a href="index.php">  <img src="_pics/logo.png" alt="Mr. Spock Logo"/></a></div><?php
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

      <div class="form-wrap"><?php
  // If already logged in, go home.
  if(isset($_SESSION['user']))
  {
    header('Location:index.php');
  }
?>

<form method="POST" action="_php/login_action.php" class="column">
  <input type="email" name="email" placeholder="e-mail" <?php if(isset($email)){echo 'value="'.$email.'"';} ?>  required />
  <input type="password" name="pass" placeholder="password" required />
  <div class="bottom-row">
    <button type="submit">Login</button><a href="forgotpass.php" class="plain">Forgot Password?</a>
  </div>

  <?php
  /*
    If a redirect is set => add hidden inputs holding that info
  */
  if( isset($_GET['redirectPage']) && isset($_GET['query']) )
  {
  ?>
  <input type="hidden" name="redirectPage" value="<?php echo $_GET['redirectPage']; ?>" />
  <input type="hidden" name="redirectquery" value="<?php echo $_GET['query']; ?>" />
  <?php
  }
  ?>


</form>

        <!--form.column(method="POST" action="login_action.php")
        input(type="text" name="email" placeholder="e-mail")
        input(type="password" name="pass" placeholder="password")
        
        .bottom-row
          button(type="submit")
            | Login
          a.plain(href="forgotpass.php") Forgot Password?
        -->
      </div>
    </div>
  </div>
</body>