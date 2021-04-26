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
  WITH HIGHLIGHT
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
                           'minimum_should_match' => '90%',
                           //'operator' => 'and',
                           'fuzziness' => 'AUTO'
                        ]
                      ]
                    ],
                    ['match' => [
                        'description_abstract' => [
                           'query'     => $search,
                           'minimum_should_match' => '90%',
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
                    "description_abstract" => [
                      "pre_tags" => ["<span class=\"highlight\">"],
                      "post_tags" => ["</span>"]
                    ],
                    "contributor_author" => [
                      "pre_tags" => ["<span class=\"highlight\">"],
                      "post_tags" => ["</span>"]
                    ]
                ],
                "force_source" => true
            ],
            "suggest" => [
              "mytermsuggester" => [
                "text" => $search,
                "term" => [
                  "field" => "title"
                ]
              ]
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



<?php
  /*
    'Did you mean?' section
  */

  // Go through each suggestion
  // $wordWrapper = $response['suggest']['mytermsuggester'] holds an array for each word in search query
  // $suggestionWrapper = $response['suggest']['mytermsuggester'][i]['options'] holds an array for each suggestion for that word
  // $response['suggest']['mytermsuggester'][i]['options'][j]['text']



  /*
    Build an array for each word
  */
  $wordWrapper = $response['suggest']['mytermsuggester'];
  // Get number of words searched
  $wordCount = count($wordWrapper);

  // Array that holds every suggestion. $suggestionsArr[0] = suggestions for first word
  $suggestionsArr[] = array();

  // Go through each word
  for($i=0;$i<$wordCount;$i++)
  {
    // Get each word's suggestions
    $suggestionWrapper = $response['suggest']['mytermsuggester'][$i]['options'];

    // If there are no suggestions for a word -> add the original word and continue
    if(empty($suggestionWrapper))
    {
      $suggestionsArr[$i][] = $response['suggest']['mytermsuggester'][$i]['text'];
    }

    // Go through each suggestion for that word
    foreach ($suggestionWrapper as $suggestion) {
      $suggestionsArr[$i][] = $suggestion['text'];
    }
  }

  // Generate possible combinations of words
  /*
    HOW THIS WORKS
      - We hava stack with all the suggestions from the first word in the stack
      - We take the first item from the stack and append it with every suggestion from the next word
          - We take that and add it to the stack
      - We repeat that with every word in query

  */

  // Check to see if there are any suggestions first
  // - use this later to generate suggestions
  $areSuggestions = false;
  foreach ($suggestionsArr as $val) {
    //echo '<h2>' . count($val) , '</h2>';
    if( count($val) > 1 )
    {
      $areSuggestions = true;
    }
  }


  $newSuggestions;
  $suggestionsArrCount = count($suggestionsArr);

  $stack = new SplQueue();
  foreach ($suggestionsArr[0] as $val) {
    $stack->enqueue($val);
  }


  while( !( $stack->isEmpty() ) && $areSuggestions )
  {


    //Go through each word (starting at second)
    for( $i = 1; $i < $suggestionsArrCount; $i++ ) {

      $suggestionsCount = count($suggestionsArr[$i]);

      $first = true;
      $firstword = "";

      // Loop through each suggestion al
      for ($j = 0; $j < $suggestionsCount; $j++) {
        // DEBUG STUFF
        //echo '<h2>suggestionsCount: '. $suggestionsCount . '</h2>';
        //echo '<br/><Br/>';

        $stack->rewind();
        $top = $stack->current();

        // DEBUG STUFF
        /*
        echo '<h2>'. $i . ' + ' . $j . '</h2>';
        print_r($stack);
        echo '<br/><Br/>';

        var_dump($newSuggestions);
        echo '<br/><Br/>';

        echo '<h2>Top: '. $top . '</h2>';
        echo '<br/><Br/>';
        */

        if ( $top == $firstword || $stack->isEmpty())
        {
          break;
        }

        // Calculate string
        $newString = $top . " " . $suggestionsArr[$i][$j];

        // DEBUG STUFF
        //echo '<h2>Newstring: '. $newString . '</h2>';
        //echo '<br/><Br/>';


        // Save the first word stored,
        if($first)
        {
          $firstword = $newString;
          $first = false;
        }

        // DEBUG STUFF
        //echo '<h2>First Word: '. $firstword . '</h2>';
        //echo '<br/><Br/>';
        //echo '<h2>Stack count: '. $stack->count() . '</h2>';
        //echo '<br/><Br/>';

        // If we're on the last word, stor to $newSuggestions[] instead of $stack
        if($i == $suggestionsArrCount - 1)
        {
          $newSuggestions[] = $newString;
        }
        else
        {
          $stack->enqueue($newString);
        }

        // If we're on the last suggestion -> pop top of the stack
        if( $j == $suggestionsCount - 1)
        {
          $stack->dequeue();
        }


        // If we're on last item and top of stack doesn't match saved first word->restart loop
        if($j == $suggestionsCount - 1 && $stack->current() !== $firstword)
        {
          $j = -1;
        }

      }

    }
  }
  // DEBUG STUFF
  //var_dump($newSuggestions);
  //echo "<h2>" . $suggestionsArr[0][2] . " " . $response['suggest']['mytermsuggester'][0]['options'][0]['text'] . "</h2>"
?>

<div class="did-you-mean-wrap">

  <?php
    /*
      Show did you mean if there are suggestions
    */
    if(!empty($newSuggestions))
    {
      $newQuery = preg_replace('/\s+/', '+', $newSuggestions[0]);
      echo '
        <div class="first">Did you mean? <a href="serp.php?search=' . $newQuery . '&author=' . $_GET['author'] . '&department=' . $_GET['department'] . '&publisher=' . $_GET['publisher'] . '&from-date=' . $_GET['from-date'] . '&to-date=' . $_GET['to-date'] . '" class="link">' . $newSuggestions[0] . '</a></div><h4>All Suggestions:</h4>';
      echo '<div class="all-suggestions">';
      foreach ($newSuggestions as $val) {
        $newQuery = preg_replace('/\s+/', '+', $val);
        echo '<a href="serp.php?search=' . $newQuery . '&author=' . $_GET['author'] . '&department=' . $_GET['department'] . '&publisher=' . $_GET['publisher'] . '&from-date=' . $_GET['from-date'] . '&to-date=' . $_GET['to-date'] . '" class="link">' . $val . ',</a> ';
      }
      echo '</div>';

    }
   ?>

</div>


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


    <!--
      Item info
    -->
    <?php
    /*
      For highlighting, We need to make sure the highlighted items actually exist in return results
        - If it doesn't exist, we need to show plain returned text
    */

    $title = $data['title'];
    if(isset($item['highlight']['title'][0]))
    {
      $title = $item['highlight']['title'][0];
    }

    $contributor_author = $data['contributor_author'];
    if(isset($item['highlight']['contributor_author'][0]))
    {
      $contributor_author = $item['highlight']['contributor_author'][0];
    }

    $publisher = $data['publisher'];
    if(isset($item['highlight']['publisher'][0]))
    {
      $publisher = $item['highlight']['publisher'][0];
    }

    $description_abstract = $data['description_abstract'];
    if(isset($item['highlight']['description_abstract'][0]))
    {
      $description_abstract = $item['highlight']['description_abstract'][0];
    }

    ?>
    <div class="item-info">
      <div class="title"><a href="page.php?id=<?php echo $item_id . '&' . $_SERVER['QUERY_STRING']; ?>"><?php echo $title;?></a></div>
      <div class="authors">Authors: <?php echo $contributor_author;?></div>
      <div class="publishers">Publisher: <?php echo $publisher;?></div>
      <div class="desc">
          <?php
            if( !empty($data['description_abstract']) )
            {
              //echo substr(filter_var($description_abstract, FILTER_SANITIZE_STRING),0,550);

              // Let's user strip_tags instead of 'filter_var' so we can keep the highlight span
              echo substr(strip_tags($description_abstract, '<span>'),0,550);
              //echo $description_abstract;
            }
          ?>
          ( <a href="page.php?id=<?php echo $item_id . '&' . $_SERVER['QUERY_STRING']; ?>">Read More</a> )
      </div>
    </div>

      <?php
        /*
          We need to check if there are documents loaded
        */
        if(isset($downloads) && !empty($downloads))
        {
      ?>
      <div class="downloads-wrap">
        <h3>Downloads:</h3>

        <?php
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

    <?php
      }
    ?>
    </div>
    <?php
    } // foreach item end
    ?>

</div>
