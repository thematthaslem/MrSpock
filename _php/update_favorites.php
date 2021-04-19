<?php

  require('connect.php');

  $fav_type = $_POST['fav_type'];
  $art_id = $_POST['art_id'];
  $user = $_POST['user'];
  $art_title = $_POST['art_title'];
  $art_date = $_POST['art_date'];
  $art_author = $_POST['art_author'];

  $conn = db_connect();

  // If 'fav_type' == 'add' -> Insert into 'favorites' database
  if($fav_type == "add")
  {
    $data = $conn->prepare('INSERT INTO favorites (art_id, user, art_title, art_date, art_author) VALUES (:art_id, :user, :art_title, :art_date, :art_author)');
    $data->bindparam(':art_id', $art_id);
    $data->bindparam(':user', $user);
    $data->bindparam(':art_title', $art_title);
    $data->bindparam(':art_date', $art_date);
    $data->bindparam(':art_author', $art_author);
  }
  if($fav_type == "remove")
  {
    $data = $conn->prepare('DELETE FROM favorites WHERE user = :user AND art_id = :art_id');
    $data->bindparam(':user', $user);
    $data->bindparam(':art_id', $art_id);
  }

  if($data->execute())
  {
    echo 'success';
  }
  else
  {
    echo 'fail';
  }



?>
