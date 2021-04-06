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
<body class="page">
  <div class="all-content page"><div class="top-bar">
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
      <div class="results-wrap">        <?php
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
  <a class="go-back-button button" href="#">< Back to Resultss</a>
  <div class="item">
    <div class="item-info">
      <div class="title"><a href="page.php?id=<?php echo $item_id; ?>"><?php echo $data['title'];?></a></div>
      <div class="info-item"><span class="key">Authors: </span><?php echo $data['contributor_author'];?></div>
      <div class="info-item"><span class="key">Publisher: </span><?php echo $data['publisher'];?></div>
      <div class="info-item"><span class="key">University: </span><?php echo $data['degree_grantor'];?></div>
      <div class="info-item"><span class="key">Department: </span><?php echo $data['contributor_department'];?></div>
      <div class="info-item"><span class="key">Academic Field: </span><?php echo $data['contributor_department'];?></div>
      <div class="info-item"><span class="key">Degree Level: </span><?php echo $data['degree_level'];?></div>
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
      </div>
    </div>
  </div>
</body>