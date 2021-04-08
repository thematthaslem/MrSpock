<?php
  /*if(!isset($_SESSION))
  {
    session_start();
  }*/
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<html> </html>
<head>
  <title>Mr. Spock - Register</title>
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
      <div class="logo-wrap">  <a href="index.php"> <img src="_pics/logo.png" alt="Mr. Spock Logo"/></a></div><?php
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

      <div class="form-wrap"> <?php
  // If already logged in, go home.
  if( isset($_SESSION['user']) )
  {
    header('Location:index.php');
  }
?>

<form method="POST" action="_php/register_action.php" class="column">
  <input type="email" name="email" placeholder="e-mail" <?php if(isset($email)){echo 'value="'.$email.'"';} ?>  required />
  <input type="password" name="pass1" placeholder="password" required />
  <input type="password" name="pass2" placeholder="re-enter password" required />
  <button type="submit">Register</button>
</form>

        <!--form.column(method="POST" action="_php/register_action.php")-->
        <!--  input(type="email" name="email" placeholder="e-mail") -->
        <!--  input(type="password" name="pass1" placeholder="password")-->
        <!--  input(type="password" name="pass2" placeholder="re-enter password") -->
        <!--  button(type="submit")-->
        <!--    | Register-->
        
      </div>
    </div>
  </div>
</body>