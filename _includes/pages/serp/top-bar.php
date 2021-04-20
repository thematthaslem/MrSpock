<?php
  /*
      Get search info
  */

  // Search Query
  if(isset($_GET['search']))
  {
    $search = filter_var($_GET['search'], FILTER_SANITIZE_STRING);
  }
  else {$search = "";}

  // Author
  if(isset($_GET['author']))
  {
    $author = filter_var($_GET['author'], FILTER_SANITIZE_STRING);
  }
  else {$author = "";}

  // Department
  if(isset($_GET['department']))
  {
    $department = filter_var($_GET['department'], FILTER_SANITIZE_STRING);
  }
  else {$department = "";}

  // Publisher
  if(isset($_GET['publisher']))
  {
    $publisher = filter_var($_GET['publisher'], FILTER_SANITIZE_STRING);
  }
  else {$publisher = "";}

  /*
  $author = filter_var($_GET['author'], FILTER_SANITIZE_STRING);
  $department = filter_var($_GET['department'], FILTER_SANITIZE_STRING);
  $publisher = filter_var($_GET['publisher'], FILTER_SANITIZE_STRING);
  */
  // dates
  if(isset($_GET['from-date']) && !empty($_GET['from-date']))
  {
    $from_date = $_GET['from-date'];
  }
  else
  {
    $from_date = "1000-01-01"; // Set it to a low number to give the search a wide range as default
  }

  if(isset($_GET['to-date']) && !empty($_GET['to-date']))
  {
    $to_date = $_GET['to-date'];
  }
  else
  {
    $to_date = "9999-12-30";
  }


?>

<div class="top-bar">
  <a href="index.php">
    <div class="logo-wrap"><img src="_pics/logo.png" alt="Mr. Spock Logo"/></div>
  </a>
  <div class="search-wrap-all">
    <form method="get" action="serp.php">
      <div class="search-wrap">
        <input id="search_input" type="text" name="search" placeholder="Explore new articles..." value="<?php echo $search;?>"/>
        <div class="mic-wrap">
          <img id="microphone"  src="//i.imgur.com/cHidSVu.gif" />
        </div>

        <button type="submit"><img src="_pics/search_arrow.svg" alt="search arrow"/></button>
      </div>
      <div class="advanced-search-wrap">
        <div class="link open-advanced-link"><span class="open-advanced">Advanced Search</span></div>
        <div class="advanced-search-items">
          <div class="items-wrap">

            <label for="author-input">Author:</label>
            <input type="text" id="author-input" name="author" value="<?php echo $author; ?>"/>

            <label for="department-input">Department:</label>
            <input type="text" id="department-input" name="department" value="<?php echo $department; ?>"/>

            <label for="publisher-input">Publisher:</label>
            <input type="text" id="publisher-input" name="publisher" value="<?php echo $publisher; ?>"/>

            <label for="date" class="date-range">Date Range:</label>
            <div class="row">
              From: <input type="date" name="from-date" value="<?php echo $from_date; ?>" />
              To: <input type="date" name="to-date" value="<?php echo $to_date; ?>" />
            </div>
            <button type="submit">Search</button>


          </div>
        </div>
      </div>
    </form>
  </div>

  <div class="nav-wrap">
    <?php
    if(isset($_SESSION['user']))
    {
    ?>
    <a class="link" href="favorites.php">My Favorites</a>
    <a class="link" href="add-document.php">+ Add New Document</a>
    <?php
    }
    ?>
  </div>
</div>
