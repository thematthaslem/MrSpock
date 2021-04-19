<div class="favorites-wrap">

<?php

  /*
    Go through each of users favorites and list them
  */

  require('_php/connect.php');
  $conn = db_connect();

  $user = $_SESSION['user'];

  $data = $conn->prepare('SELECT * FROM favorites WHERE user = :user');
  $data->bindparam(':user', $user);

  $data->execute();

  $arr = $data->fetchAll(PDO::FETCH_ASSOC);

  foreach ($arr as $val) {

?>



    <div class="item">

      <!-- The remove favorite button. Holds all data needed to enter it into database -->

      <img class="favorite-button selected remove-item"
          data-id="<?php echo $val['art_id']; ?>"
          data-user="<?php echo $_SESSION['user'];?>"
          data-title="<?php echo $val['art_title'];?>"
          data-date="<?php echo $val['art_date']; ?>"
          data-author="<?php echo $val['art_author'];?>"
      src="_pics/x.svg" />

      <a href="page.php?id=<?php echo $val['art_id']; ?>" target="_blank">
        <span>[#<?php echo $val['art_id']; ?>]</span>
        <span class="title"><?php echo $val['art_title']; ?></span><br/><br/>
        by <span class="author"><?php echo $val['art_author']; ?></span>
        (<span class="date"><?php echo $val['art_date']; ?></span>)
      </a>
    </div>

<?php
  }
?>
</div>
