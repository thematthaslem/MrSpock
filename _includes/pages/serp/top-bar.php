<?php
  /*
      Get search info
  */
  $search = filter_var($_GET['search'], FILTER_SANITIZE_STRING);
  $author = filter_var($_GET['author'], FILTER_SANITIZE_STRING);
  $publisher = filter_var($_GET['publisher'], FILTER_SANITIZE_STRING);


?>

<div class="top-bar">
  <a href="index.php">
    <div class="logo-wrap"><img src="_pics/logo.png" alt="Mr. Spock Logo"/></div>
  </a>
  <div class="search-wrap-all">
    <form>
      <div class="search-wrap">
        <input type="text" name="search" placeholder="Explore new articles..." value="<?php echo $search;?>"/>
        <button type="submit"><img src="_pics/search_arrow.svg" alt="search arrow"/></button>
      </div>
      <div class="advanced-search-wrap">
        <div class="link"><span class="open-advanced">Advanced Search</span></div>
        <div class="advanced-search-items">
          <div class="items-wrap">
            <label for="author-input">Author:</label>
            <input type="text" id="author-input" name="author" value="<?php echo $author; ?>"/>
            <label for="publisher-input">Publisher:</label>
            <input type="text" id="publisher-input" name="publisher" value="<?php echo $publisher; ?>"/>
          </div>
        </div>
      </div>
    </form>
  </div>

  <div class="nav-wrap">
    <a class="link" href="add-document.php">+ Add New Document</a>
  </div>
</div>
