<?php
  require 'vendor/autoload.php';
  $client = Elasticsearch\ClientBuilder::create()->build();

/*
  THIS IS FOR IF THE AUTHORS AND PUBLISHERS DONT NEED TO BE AN EXACT MATCH
  LIKE IF THE INPUTTED AUTHOR ISN'T ACTUALLY THE AUTHOR OF AN ARTICLE, THAT ARTICLE MIGHT STILL POP UP

  $params = [
       'index' => 'test_index',
       'body' => [
           'sort' => [
               '_score'
           ],
           'query' => [
              'bool' => [
                  'should' => [
                       ['match' => [
                           'title' => [
                              'query'     => $search,
                              'fuzziness' => '2'
                           ]
                       ]],
                       ['match' => [
                           'publisher' => [
                               'query'     => $publisher,
                               'fuzziness' => '1'
                           ]
                       ]],
                       ['match' => [
                           'contributor_author' => [
                               'query'     => $author,
                               //'fuzziness' => '1'
                           ]
                       ]]
                  ]
               ],
           ],
        ]
   ];
*/
   $params = [
        'index' => 'test_index',
        'body' => [
            'sort' => [
                '_score'
            ],
            'query' => [
               'bool' => [
                   'should' => [
                        ['match' => [
                            'title' => [
                               'query'     => $search,
                               'fuzziness' => '2'
                            ]
                        ]]
                   ],
                   'must' => [
                      ['match' => [
                          'publisher' => [
                            'query' => $publisher,
                            'zero_terms_query' => 'all',
                            'fuzziness' => '1'
                          ]
                        ]
                      ],
                      ['match' => [
                          'contributor_author' => [
                            'query' => $author,
                            'zero_terms_query' => 'all',
                            'fuzziness' => '1'
                          ]
                        ]
                      ]
                   ]
                ],
            ],
         ]
    ];



  //print_r($params);
  //echo '<br/><br/>';
  $response = $client->search($params);
  //print_r($response);

  $item_count = $response['hits']['total']['value'];
  $items = $response['hits']['hits'];
?>


<h1>Results</h1>
<h2>Found <?php echo $item_count; ?> Results</h2>


<div class="items-wrap">
<?php
  foreach ($items as $item) {
    //print_r($item);
    $data = $item['_source'];
    if(isset($data['relation_haspart']))
    {
      $downloads = $data['relation_haspart'];
    }
    $item_id = $item['_id'];
    $handle = $data['handle'];  // This is the identifier for the item
                                // It's like the folder name it came from
?>
  <div class="item">
    <div class="item-info">
      <div class="title"><a href="page.phpid=<?php echo $item_id; ?>"><?php echo $data['title'];?></a></div>
      <div class="authors">Authors: <?php echo $data['contributor_author'];?></div>
      <div class="publishers">Publisher: <?php echo $data['publisher'];?></div>
      <div class="desc">
          <?php
            if( !empty($data['description_abstract']) )
            {
              echo substr($data['description_abstract'],0,550);
            }
          ?>
          ( <a href="page.phpid=<?php echo $item_id; ?>">Read More</a> )
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
          <div class="title"><?php echo substr($download,0,16); ?></div>
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
    }
    ?>
</div>
