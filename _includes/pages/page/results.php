<?php
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

  $response = $client->get($params);

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

  <!--
    Go Back button
  -->
  <?php
    if(isset($_GET['search']))
    {
  ?>
  <a class="go-back-button button" href="serp.php?search=<?php echo $_GET['search']; ?>&author=<?php echo $_GET['author']; ?>&department=<?php echo $_GET['department']; ?>&publisher=<?php echo $_GET['publisher']; ?>&from-date=<?php echo $_GET['from-date']; ?>&to-date=<?php echo $_GET['to-date']; ?><?php if(isset($_GET['page'])){echo '&page=' . $_GET['page'];}?>">< Back to Results</a>
  <?php
    }
  ?>


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
      <a class="download-button-wrap" href="_files_dissertation/<?php echo "$handle/$download";?>" download>
        <div class="download-button">Download</div>
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
      <a class="download-button-wrap" href="_files_dissertation/<?php echo "$handle/$downloads";?>" download>
        <div class="download-button">Download</div>
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
