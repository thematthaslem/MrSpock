<?php
  // If not logged in, don't show login/register buttons
  if ( !($loggedin) )
  {
?>
  <div class="login-register-buttons-wrap"><a href="login.php" class="button light">Login</a><a href="register.php" class="button outline">Sign Up  </a></div>
<?php
  }
?>
