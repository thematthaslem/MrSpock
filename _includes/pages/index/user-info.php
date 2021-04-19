<?php
  require('_php/connect.php');
  require('_php/functions_get.php');
  $loggedin = false;
  if( check_logged_in() )
  {
    $loggedin = true;
  }

  if($loggedin)
  {

?>

<div class="user-info-wrap">
  <div class="user-info"><img src="_pics/user.png" class="user-pic"/><a href="editinfo.php"><span class="username"><?php echo $_SESSION['user']; ?></span></a><a class="button dropdown" data-target="user-options-wrap"><img src="_pics/drop_arrow.svg" alt="dropdown arrow"/></a></div>
  <div class="user-options-wrap">
    <div class="user-options-buttons">
      <a href="editinfo.php">Edit Info</a>
      <a href="add-document.php">Add New Document</a>
      <a href="favorites.php">My Favorites</a>
      <a href="_php/logout.php">Logout</a>
    </div>

  </div>
</div>


<?php
}

?>
