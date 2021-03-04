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
