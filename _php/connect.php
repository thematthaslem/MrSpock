<?php
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }


  function db_connect()
  {
    $host = "localhost";
    $username = "root";
    $dbname = "mr_spock";
    $pass = "";

    try
    {
      $conn = new PDO("mysql:host=$host;dbname=$dbname;", $username, $pass);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      //echo "Connected successfully";
    }
    catch (PDOException $e)
    {
      die("There was a problem connecting to server.<br/> Details: " . $e);
    }

    return $conn;

  }

/*
** Test Connection by grabbing from users table

  $id = 1;
  $conn = db_connect();
  $data = $conn->prepare("SELECT * FROM users WHERE id = :id");
  $data->bindparam(':id', $id);
  $data->execute();

  $arr = $data->fetchAll(PDO::FETCH_ASSOC);

  print_r($arr);

*/
?>
