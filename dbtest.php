<?php
  $server = "localhost";
  $user = "root";
  $db = "test";

  $conn = new mysqli($server, $user, "");

  if($conn->connect_error)
  {
    die("FAILED:: " . $conn->connect_error);
  }
  echo "SUCCESS!";
?>
