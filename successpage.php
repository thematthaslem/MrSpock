<?php
  /*if(!isset($_SESSION))
  {
    session_start();
  }*/
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<html></html>
<head>
  <title>Mr. Spock</title>
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
      <div class="logo-wrap"> <a href="index.php"> <img src="_pics/logo.png" alt="Mr. Spock Logo"/></a></div><?php
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
<a href="index.php" class="button light">Go Back</a>
    </div>
  </div>
</body>