<?php
  session_start();
  // If logged in -> destroy all session
  if( isset($_SESSION['user']) )
  {

    session_unset();
    session_destroy();
  }

  // If not logged in just go back to home... gonna do that either way
  header('Location: ../index.php');

?>
