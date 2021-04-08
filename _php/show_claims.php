<?php
  require('connect.php');

  $art_id = $_POST['art_id'];

  $conn = db_connect();

  $data = $conn->prepare('SELECT * FROM claims WHERE art_id = :art_id');
  $data->bindparam(':art_id', $art_id);

  $data->execute();

  $arr = $data->fetchAll(PDO::FETCH_ASSOC);

  $i = 1;
  foreach($arr as $row){
    $title = $row['title'];
    $author = $row['author'];
    $reproduce = $row['reproduce'];
    $source = $row['source'];
    $datasets = $row['datasets'];
    $results = $row['results'];
    echo "
      <div class=\"item-wrapper\">
        <div class=\"title\">#$i - $title</div>
        <div class=\"info\">By: <span class=\"value\">$author</span></div>
        <div class=\"info\">Can reproduce?: <span class=\"value\">$reproduce</span></div>
        <div class=\"info\">Source Code: <span class=\"value\">$source</span></div>
        <div class=\"info\">Datasets: <span class=\"value\">$datasets</span></div>
        <div class=\"info\">
          Results:
          <p>
            $results
          </p>
        </div>
      </div>
    ";
  }

  // If the array is empty, then there are no claims. State that
  if(empty($arr))
  {
    echo '<h2>There are no claims for this document.</h2>';
  }

?>
