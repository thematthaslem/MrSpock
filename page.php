<?php
  /*if(!isset($_SESSION))
  {
    session_start();
  }*/
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<html>       </html>
<head>                      
  <title>Mr. Spock - Page           </title>
  <link rel="stylesheet" type="text/css" href="main.css"/>
  <script src="_jquery/jquery-3.3.1.min.js"></script>
  <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <script src="_jquery/jq.js"></script>
</head>
<body class="page">  
  <div class="all-content page"> <?php
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
        <input type="text" name="search" placeholder="Explore new articles..." value="<?php echo $search;?>"/>
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

    <!--.top-bar         
    .logo-wrap   
      img(src="_pics/logo.png" alt="Mr. Spock Logo") 
    .search-wrap-all  
      form 
        .search-wrap
          input(type="text" name="search" placeholder="Explore new articles...")
          button(type="submit")
            img(src="_pics/search_arrow.svg" alt="search arrow")
    
        .advanced-search-wrap   
          .link
            span.open-advanced Advanced Search   
              
          .advanced-search-items  
            .items-wrap  
              label(for="author-input") Author: 
              input(type="text" id="author-input" name="author") 
              label(for="publisher-input") Publisher:
              input(type="text" id="publisher-input" name="publisher") 
    
    -->
    <div class="main-content-wrap">    
      <div class="results-wrap">              <?php
  require '_php/functions_get.php';
  require '_php/connect.php';

  require 'vendor/autoload.php';
  $client = Elasticsearch\ClientBuilder::create()->build();

  if(isset($_GET['id']))
  {
    $doc_id = $_GET['id'];
  }
  else
  {
    header('Location:index.php');
  }


$params = [
  'index' => 'test_index',
  'id' => $doc_id
];

  //print_r($params);
  //echo '<br/><br/>';
  $response = $client->get($params);
  //print_r($response);

  $data = $response['_source'];
?>


<div class="items-wrap">
<?php
    //print_r($item);
    if(isset($data['relation_haspart']))
    {
      $downloads = $data['relation_haspart'];
    }
    $handle = $data['handle'];  // This is the identifier for the item
                                // It's like the folder name it came from
?>
  <a class="go-back-button button" href="#">< Back to Results</a>



  <!--
    FAVORITE BUTTON
  -->
  <?php
  /*
    Only show favorite button if user is logged in
    - If it's already favorited -> give it class selected
  */
  $selected = "";
  if(isset($_SESSION['user']))
  {
    $favorite_item = get_favorite($_GET['id']);
    if(sizeof($favorite_item) > 0)
    {
      $selected = "selected";
    }
  ?>
  <a class="button favorite-button page-button <?php echo $selected; ?>"
      data-id="<?php echo $_GET['id']; ?>"
      data-user="<?php echo $_SESSION['user'];?>"
      data-title="<?php echo $data['title'];?>"
      data-date="<?php echo $data['date_issued']; ?>"
      data-author="<?php echo $data['contributor_author'];?>"
      href="#">

      <?php if($selected == "selected") { ?>
      <div class="image-placeholder"></div> <span>Remove From Favorites</span>
      <?php }
      else {?>
      <div class="image-placeholder"></div> <span>Add to Favorites</span>
      <?php }?>
  </a>
  <?php
  }
  ?>



  <div class="item">
    <div class="item-info">
      <div class="title"><a href="page.php?id=<?php echo $item_id; ?>"><?php echo $data['title'];?></a></div>
      <div class="info-item"><span class="key">Authors: </span><?php echo $data['contributor_author'];?></div>
      <div class="info-item"><span class="key">Publisher: </span><?php echo $data['publisher'];?></div>
      <div class="info-item"><span class="key">University: </span><?php echo $data['degree_grantor'];?></div>
      <div class="info-item"><span class="key">Department: </span><?php echo $data['contributor_department'];?></div>
      <div class="info-item"><span class="key">Academic Field: </span><?php echo $data['contributor_department'];?></div>
      <div class="info-item"><span class="key">Degree Level: </span><?php echo $data['degree_level'];?></div>
      <div class="info-item"><span class="key">Date Issued: </span><?php echo $data['date_issued']; ?></div>
      <div class="info-item">
        <span class="key">Advisors: </span>
        <?php
          /*
            Print advisors. it's in an array.
          */
          $advisors = $data['contributor_committeemember'];
          $num_of_advisors = count($advisors);
          $i = 0;
          foreach ($advisors as $advisor) {
            echo $advisor;
            // If it's not the last one, print a comma
            if($i++ == $num_of_advisors)
            {
              echo ", ";
            }
          }
        ?>
      </div>

      <hr />
      <div class="desc">
          <?php
            if( !empty($data['description_abstract']) )
            {
              echo $data['description_abstract'];
            }
          ?>
      </div>
    </div>
    <div class="downloads-wrap">
      <h3>Downloads:</h3>
      <?php
        /*
          We need to check if there are documents loaded
        */
        if(isset($downloads))
        {
        /*
          We need to check if it's an array.
            - If is_array -> we need to print each item in the array
            - If not -> just print that one.
        */
        if( is_array($downloads) )
        {
          foreach ($downloads as $download) {
      ?>
      <a href="_files_dissertation/<?php echo "$handle/$download";?>" target="_blank">
        <div class="download-item">
          <img src="_pics/PDF_icon.svg"/>
          <div class="title"><?php echo $download//substr($download,0,16); ?></div>
        </div>
      </a>
      <?php
          }
        }
        else
        {
      ?>
      <a href="_files_dissertation/<?php echo "$handle/$downloads";?>" target="_blank">
        <div class="download-item">
          <img src="_pics/PDF_icon.svg"/>
          <div class="title"><?php echo $downloads; //substr($downloads,0,16); ?></div>
        </div>
      </a>
      <?php
        }
      ?>
    </div>
  </div>
    <?php
      }
    ?>
</div>

        <!--.items-wrap          
        .item           
          .item-info           
            .title A Power Conditioning System for Superconductive Magnetic Energy Storage based on Multi-Level Voltage Source Converter
            .desc 
              | Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.
          .downloads-wrap
            
            a(href="_files_dissertation/11042/ETDAppendixA.pdf")
              .download-item
                img(src="_pics/PDF_icon.svg")
                .title ETDAppendixA.pdf
            a(href="_files_dissertation/11042/ETDAppendixA.pdf")
              .download-item
                img(src="_pics/PDF_icon.svg")
                .title ETDAppendixA.pdf  
        
        -->
      </div><!--
  The Make a new claim form
-->
<div class="new-claim-wrapper">
  <h1>New Claim:</h1>
<?php
  /*
    Check if user is logged in
  */
  $loggedin = false;
  if( isset($_SESSION['user']) )
  {
    $loggedin = true;
  }


  /*
    If the user is not logged in, give them a link to log in.
  */
  if( !($loggedin) )
  {
    // Need to get the query from url to pass to login.php so we can return after logging in.
    $redirect_query = $_SERVER['QUERY_STRING'];

    echo "
      <a class=\"link\" href=\"login.php?redirectPage=page&query=$redirect_query\">Log in</a> to submit a claim.
    ";
  }
  else
  {
?>

<div class="message success">
  Claim successfully submitted!
</div>
<div class="message fail">
  Sorry! Something went wrong with submitting your claim.
</div>

<form id="new-claim" method="post" action="_php/send_claim_action.php">
  <input type="hidden" name="art_id" value="<?php echo $_GET['id'];?>" />
  <h2>Claim by <?php echo $_SESSION['user']; ?></h2>
  <input type="hidden" name="author" value="<?php echo $_SESSION['user']; ?>" />
  <label for="title">Claim:</label>
  <input type="text" name="title" required />
    <label for="reproduce">Can you reproduce this claim?</label>
  <div class="row">
    <a class="button reproduce-button active">Yes</a>
    <a class="button reproduce-button">No</a>
    <a class="button reproduce-button">Partially</a>
  </div>
  <input name="reproduce" type="hidden" value="Yes" />
  <h3>Proof of experiments:</h3>
  <label for="source-code">Source Code:</label>
  <input name="source-code" type="text" required />
  <label for="datasets">Datasets:</label>
  <input name="datasets" type="text" required />
  <label for="results">Experiments and Results</label>
  <textarea name="results" required>

  </textarea>

  <button type="submit" id="submit-claim" class="button">Submit</button>




</form>

<?php
  }
?>

</div>
<!--
  Show claims
-->
<div class="claims-wrapper">
  <h1>Claims:</h1>

  <input id="art_id" type="hidden" value="<?php echo $_GET['id'];?>" />

  <div class="claim-items-wrapper">
    <div class="item-wrapper">
      <div class="title">#1 - This is a Claim</div>
      <div class="info">By: <span class="value">Matthew Haslem</span></div>
      <div class="info">Can reproduce?: <span class="value">Partially</span></div>
      <div class="info">Source Code: <span class="value">http://localhost:5601/app/dev_tools#/console</span></div>
      <div class="info">Datasets: <span class="value">http://localhost:5601/app/dev_tools#/console</span></div>
      <div class="info">
        Results:
        <p>
          "Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit..
        </p>
      </div>
    </div>
    <div class="item-wrapper">
      <div class="row">
        <div class="title">#1 - This is a Claim</div>

        <!--
          VOTE BUTTONS
          - only allow voting if user is logged in
          - disable voting by adding class 'not-logged-in'
        -->
        <?php
          $notLoggedIn = "";
          if( !(isset($_SESSION['user'])) )
          {
            $notLoggedIn = "not-logged-in";
          }
          ?>
          <div class="vote-buttons-wrap">
            <div class="votes">130</div>
            <div class="vote-button upvote <?php echo $notLoggedIn; ?>"><div class="count">65</div></div>
            <div class="vote-button downvote <?php echo $notLoggedIn; ?>"><div class="count">65</div></div>
          </div>
          
      </div>

      <div class="info">By: <span class="value">Matthew Haslem</span></div>
      <div class="info">Can reproduce?: <span class="value">Partially</span></div>
      <div class="info">Source Code: <span class="value">http://localhost:5601/app/dev_tools#/console</span></div>
      <div class="info">Datasets: <span class="value">http://localhost:5601/app/dev_tools#/console</span></div>
      <div class="info">
        Results:
        <p>
          "Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit..
        </p>
      </div>
    </div>
  </div>

</div>

    </div>
  </div>
</body>