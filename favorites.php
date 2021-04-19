<?php
  /*if(!isset($_SESSION))
  {
    session_start();
  }*/
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<html> </html>
<head>  
  <title>Mr. Spock - Edit Info  </title>
  <link rel="stylesheet" type="text/css" href="main.css"/>
  <script src="_jquery/jquery-3.3.1.min.js"></script>
  <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <script src="_jquery/jq.js"></script>
</head>
<body> 
  <div class="all-content edit-info"> 
    <div class="left-bar">
      <div class="logo-wrap"> <img src="_pics/logo.png" alt="Mr. Spock Logo"/></div>
      <div class="nav-wrap">
        <div class="nav-links"><a href="index.php">Home </a><a href="editinfo.php">Edit Info </a><a href="_php/logout.php">Logout </a></div>
      </div>
    </div>
    <div class="content-wrap">
      <h1>My Favorites: </h1><div class="favorites-wrap">

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

    </div>
  </div>
</body>