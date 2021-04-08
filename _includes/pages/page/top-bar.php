<div class="top-bar">
  <a href="index.php">
    <div class="logo-wrap"><img src="_pics/logo.png" alt="Mr. Spock Logo"/></div>
  </a>
  <div class="search-wrap-all">
    <form action="serp.php" method="get">
      <div class="search-wrap">
        <input type="text" name="search" placeholder="Explore new articles..."/>
        <button type="submit"><img src="_pics/search_arrow.svg" alt="search arrow"/></button>
      </div>
      <div class="advanced-search-wrap">
        <div class="link"><span class="open-advanced">Advanced Search</span></div>
        <div class="advanced-search-items">
          <div class="items-wrap">
            <label for="author-input">Author:</label>
            <input type="text" id="author-input" name="author" />
            <label for="publisher-input">Publisher:</label>
            <input type="text" id="publisher-input" name="publisher" />
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
    <a class="link" href="add-document.php">+ Add New Document</a>
    <?php
    }
    ?>
  </div>
</div>
