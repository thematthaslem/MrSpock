<?php
  require '_php/functions_get.php';
  require '_php/connect.php';

  require 'vendor/autoload.php';
  $client = Elasticsearch\ClientBuilder::create()->build();


  // The number of results per page
  $page_size = 5;

  /*
   Calculate starting point for results on this page

    - There are 10 results per page.
      -- Starting result = 10 * page # (if there is no page #, make it zero)
  */
  if(isset($_GET['page']))
  {
    $curr_page_number = $_GET['page'];
  }
  else
  {
    $curr_page_number = 0;
  }

  $start_of_results = $page_size * $curr_page_number;




  /*
    SEARCH AND FILTER
  */

/*
  $params = [
       'index' => 'test_index',
       'body' => [
           'sort' => [
               '_score'
           ],
           'from' => $start_of_results,
           'size' => $page_size,
           'query' => [
              'bool' => [

                  'must' => [
                    ['match' => [
                        'title' => [
                           'query'     => $search,
                           'minimum_should_match' => '50%'
                           //'operator' => 'and'
                           //'fuzziness' => '2'
                        ]
                      ]
                    ],
                     ['match' => [
                         'publisher' => [
                           'query' => $publisher,
                           'zero_terms_query' => 'all',
                           'fuzziness' => '1'
                         ]
                       ]
                     ],
                     ['match' => [
                         'contributor_department' => [
                           'query' => $department,
                           'operator' => 'and',
                           'zero_terms_query' => 'all',
                           'fuzziness' => '1'
                         ]
                       ]
                     ],
                     ['match' => [
                         'contributor_author' => [
                           'query' => $author,
                           'operator' => 'and',
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
*/


/*
THIS ONE

  $params = [
       'index' => 'test_index',
       'body' => [
           'sort' => [
               '_score'
           ],
           'from' => $start_of_results,
           'size' => $page_size,
           'query' => [
              'bool' => [
                  'filter' => [
                    'range' => [
                      'date_issued' => [
                        'gte' => $from_date,
                        'lte' => $to_date
                      ]
                    ]
                  ],

                  'must' => [
                    ['match' => [
                        'title' => [
                           'query'     => $search,
                           'minimum_should_match' => '50%'
                           //'operator' => 'and'
                           //'fuzziness' => '2'
                        ]
                      ]
                    ],
                     ['match' => [
                         'publisher' => [
                           'query' => $publisher,
                           'zero_terms_query' => 'all',
                           'fuzziness' => '1'
                         ]
                       ]
                     ],
                     ['match' => [
                         'contributor_department' => [
                           'query' => $department,
                           'operator' => 'and',
                           'zero_terms_query' => 'all',
                           'fuzziness' => '1'
                         ]
                       ]
                     ],
                     ['match' => [
                         'contributor_author' => [
                           'query' => $author,
                           'operator' => 'and',
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
*/


/*
  WITH HIGHLIGH
*/
  $params = [
       'index' => 'test_index',
       'body' => [
           'sort' => [
               '_score'
           ],
           'from' => $start_of_results,
           'size' => $page_size,
           'query' => [
              'bool' => [
                  'filter' => [
                    'range' => [
                      'date_issued' => [
                        'gte' => $from_date,
                        'lte' => $to_date
                      ]
                    ]
                  ],

                  'must' => [
                    ['match' => [
                        'title' => [
                           'query'     => $search,
                           'minimum_should_match' => '50%'
                           //'operator' => 'and'
                           //'fuzziness' => '2'
                        ]
                      ]
                    ],
                     ['match' => [
                         'publisher' => [
                           'query' => $publisher,
                           'zero_terms_query' => 'all',
                           'fuzziness' => '1'
                         ]
                       ]
                     ],
                     ['match' => [
                         'contributor_department' => [
                           'query' => $department,
                           'operator' => 'and',
                           'zero_terms_query' => 'all',
                           'fuzziness' => '1'
                         ]
                       ]
                     ],
                     ['match' => [
                         'contributor_author' => [
                           'query' => $author,
                           'operator' => 'and',
                           'zero_terms_query' => 'all',
                           'fuzziness' => '1'
                         ]
                       ]
                     ]
                  ]
               ],
           ],
           "highlight" => [
                "pre_tags" => ["<b>"],
                "post_tags" => ["</b>"],
                "fields" => [
                    "title" => [
                      "pre_tags" => ["<span class=\"highlight\">"],
                      "post_tags" => ["</span>"]
                    ],
                    "publisher" => [
                      "pre_tags" => ["<span class=\"highlight\">"],
                      "post_tags" => ["</span>"]
                    ],
                    "contributor_author" => [
                      "pre_tags" => ["<span class=\"highlight\">"],
                      "post_tags" => ["</span>"]
                    ]
                ],
                "force_source" => true
            ]
        ]
   ];


  /*
    THIS IS FOR IF THE AUTHORS AND PUBLISHERS DONT NEED TO BE AN EXACT MATCH
    LIKE IF THE INPUTTED AUTHOR ISN'T ACTUALLY THE AUTHOR OF AN ARTICLE, THAT ARTICLE MIGHT STILL POP UP
  */
  /*
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
                                 //'fuzziness' => '1'
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


  $response = $client->search($params);
  $item_count = $response['hits']['total']['value'];
  $items = $response['hits']['hits'];



  /*
   Calculate the number of pages
    - There are 10 results per page.
      -- # of pages = the number of items / 10
  */

  $number_of_pages = ceil($item_count / $page_size);

?>





<h1>Results</h1>
<h2>Found <?php echo $item_count; ?> Results for "<?php echo $search; ?>"</h2>


<?php
  /*
    Show pagination at the top of the page
  */
  include('_includes/pages/serp/pagination.php');
?>

<div class="items-wrap">
<?php
  foreach ($items as $item) {
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

    <?php
    /*
      Only show favorite button if user is logged in
      - If it's already favorited -> give it class selected
    */
    $selected = "";
    if(isset($_SESSION['user']))
    {
      $favorite_item = get_favorite($item_id);
      if(sizeof($favorite_item) > 0)
      {
        $selected = " selected";
      }
    ?>
    <div class="favorite-wrap">
      <!-- The favorite button. Holds all data needed to enter it into database -->
      <img class="favorite-button <?php echo $selected; ?>"
          data-id="<?php echo $item_id; ?>"
          data-user="<?php echo $_SESSION['user'];?>"
          data-title="<?php echo $data['title'];?>"
          data-date="<?php echo $data['date_issued']; ?>"
          data-author="<?php echo $data['contributor_author'];?>"
      src="_pics/fav.svg" />
    </div>
    <?php
    }
    ?>

    <div class="item-info">
      <div class="title"><a href="page.php?id=<?php echo $item_id . '&' . $_SERVER['QUERY_STRING']; ?>"><?php echo $item['highlight']['title'][0];?></a></div>
      <div class="authors">Authors: <?php echo $data['contributor_author'];?></div>
      <div class="publishers">Publisher: <?php echo $data['publisher'];?></div>
      <div class="desc">
          <?php
            if( !empty($data['description_abstract']) )
            {
              echo substr(filter_var($data['description_abstract'], FILTER_SANITIZE_STRING),0,550);
            }
          ?>
          ( <a href="page.php?id=<?php echo $item_id . '&' . $_SERVER['QUERY_STRING']; ?>">Read More</a> )
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
          <div class="title"><?php echo $download;//substr($download,0,16); ?></div>
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
