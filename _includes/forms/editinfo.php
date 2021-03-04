<?php
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
