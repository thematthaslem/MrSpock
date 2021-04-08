<?php
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
