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
  <div class="all-content index"><?php
  require('_php/connect.php');
  require('_php/functions_get.php');
  $loggedin = false;
  if( check_logged_in() )
  {
    $loggedin = true;
  }

  if($loggedin)
  {

?>

<div class="user-info-wrap">
  <div class="user-info"><img src="_pics/user.png" class="user-pic"/><a href="editinfo.php"><span class="username"><?php echo $_SESSION['user']; ?></span></a><a class="button dropdown"><img src="_pics/drop_arrow.svg" alt="dropdown arrow"/></a></div>
  <div class="user-options-wrap">
    <a href="editinfo.php">Edit Info</a>
    <a href="add-document.php">Add New Document</a>
    <a href="_php/logout.php">Logout</a>
  </div>
</div>


<?php
}

?>

    <!--.user-info-wrap
    .user-info
      img(class="user-pic" src="_pics/user.png")
      span.username TheMattHaslem
      a.button.dropdown 
        img(src="_pics/drop_arrow.svg" alt="dropdown arrow")
    .user-options-wrap
      a(href="editinfo.php") Edit Info
      a(href="logout.php") Logout
    
    -->
    <div class="content-wrap">
      <div class="logo-wrap"><img src="_pics/logo.png" alt="Mr. Spock Logo"/></div>
      <div class="search-wrap-all">
        <form method="GET" action="serp.php">
          <div class="search-wrap">
            <input type="text" name="search" placeholder="Explore new articles..."/>
            <button type="submit"><img src="_pics/search_arrow.svg" alt="search arrow"/></button>
          </div>
          <div class="advanced-search-wrap">
            <div class="link"><span class="open-advanced">Advanced Search </span></div>
            <div class="advanced-search-items">
              <label for="author-input">Author:</label>
              <input type="text" id="author-input" name="author"/>
              <label for="department-input">Department:</label>
              <input type="text" id="department-input" name="department"/>
              <label for="publisher-input">Publisher:</label>
              <input type="text" id="publisher-input" name="publisher"/>
            </div>
          </div>
        </form>
      </div><?php
  // If not logged in, don't show login/register buttons
  if ( !($loggedin) )
  {
?>
  <div class="login-register-buttons-wrap"><a href="login.php" class="button light">Login</a><a href="register.php" class="button outline">Sign Up  </a></div>
<?php
  }
?>

    </div>
  </div>
</body>