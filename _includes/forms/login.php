<?php
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
