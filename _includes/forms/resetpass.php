<form method="POST" action="_php/resetpass_action.php" class="column">
  <input type="password" name="pass1" placeholder="password" required/>
  <input type="password" name="pass2" placeholder="re-enter password" required/>
  <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>" >
  <button type="submit">Reset Pass</button>
</form>
