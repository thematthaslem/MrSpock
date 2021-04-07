<?php
  /*
    Create a new claim and add it to 'claims' database
  */
  require('connect.php');

  $art_id = $_POST['art_id'];
  $title = $_POST['title'];
  $author = $_POST['author'];
  $reproduce = $_POST['reproduce'];
  $source = $_POST['source-code'];
  $datasets = $_POST['datasets'];
  $results = $_POST['results'];

  $conn = db_connect();
  $data = $conn->prepare("
    INSERT INTO claims (art_id, title, author, reproduce, source, datasets, results)
    VALUES (:art_id, :title, :author, :reproduce, :source, :datasets, :results)"
  );

  $data->bindparam(':art_id', $art_id);
  $data->bindparam(':title', $title);
  $data->bindparam(':author', $author);
  $data->bindparam(':reproduce', $reproduce);
  $data->bindparam(':source', $source);
  $data->bindparam(':datasets', $datasets);
  $data->bindparam(':results', $results);

  if($data->execute())
  {
    echo "success";
  }
  else
  {
    echo "fail";
  }


?>
