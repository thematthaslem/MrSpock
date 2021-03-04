<?php
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
