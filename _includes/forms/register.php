<?php
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
