<?php
  /*if(!isset($_SESSION))
  {
    session_start();
  }*/
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<html>     </html>
<head>                      
  <title>Mr. Spock - Search Results            </title>
  <link rel="stylesheet" type="text/css" href="main.css"/>
  <script src="_jquery/jquery-3.3.1.min.js"></script>
  <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <script src="_jquery/jq.js"></script>
</head>
<body class="serp">        
  <div class="all-content serp">                        <div class="top-bar">
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
</div>

    <div class="main-content-wrap add-document">
      <div class="results-wrap">  
<?php
  $is_messages = false;

  if( isset($_SESSION['success']) )
  {
      $is_messages = true;
      $message_type = "Success";
      $messages = $_SESSION['success'];
      unset($_SESSION['success']);
  }
  if( isset($_SESSION['error']) )
  {
    $is_messages = true;
    $message_type = "Error";
    $messages = $_SESSION['error'];
    unset($_SESSION['error']);
  }

  /*
    Make sure they're logged in
  */
  if( !(isset($_SESSION['user'])) )
  {
    header('Location: login.php?redirectPage=add-document&query=');
  }

  if($is_messages)
  {
?>

  <div class="message-wrap <?php echo $message_type; ?>">
    <h3><?php echo $message_type; ?></h3>
    <?php foreach ($messages as $item): ?>
      <div class="item">
        <?php echo $item; ?>
      </div>
    <?php endforeach; ?>
  </div>

<?php
  }
?>

        <h1>New Document</h1>
        <div class="new-document-form-wrap">
          <form method="POST" id="new-document" action="_php/new_document_action.php" enctype="multipart/form-data">
            <label for="title">Title:</label>
            <input type="text" name="title" required="required"/>
            <label for="contributor_author">Contributor Author:</label>
            <input type="text" name="contributor_author" required="required"/>
            <label for="contributor_committeechair">Contributor Committee Chair:</label>
            <input type="text" name="contributor_committeechair" required="required"/>
            <label for="contributor_committeemember">Contributor Committee Members:</label>
            <input type="text" name="contributor_committeemember" placeholder="Seperate by comma" required="required"/>
            <label for="contributor_department">Contributor Department: </label>
            <input type="text" name="contributor_department" required="required"/>
            <label for="date">Date Issued:</label>
            <input type="date" name="date" required="required"/>
            <label for="degree_grantor">Degree Grantor:</label>
            <input type="text" name="degree_grantor" required="required"/>
            <label for="degree_level">Degree Level:</label>
            <input type="text" name="degree_level" value="doctoral" required="required"/>
            <label for="description_abstract">Description Abstract:</label>
            <textarea name="description_abstract" required="required"></textarea>
            <label for="description_degree">Description Degree:</label>
            <input type="text" name="description_degree" required="required"/>
            <label for="description_provenance">Description Provenance:</label>
            <input type="text" name="description_provenance" placeholder="Seperate by comma" required="required"/>
            <label for="publisher">Publisher:</label>
            <input type="text" name="publisher" value="Virginia Tech"/>
            <label for="subject">Subject:</label>
            <input type="text" name="subject" placeholder="Seperate by comma" required="required"/>
            <label for="identifier_sourceurl">Identifier Source URL:</label>
            <input type="text" name="identifier_sourceurl" required="required"/>
            <label for="relation_haspart">File (PDF):</label>
            <input type="file" name="relation_haspart" required="required"/>
            <button type="submit">Submit Document</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>