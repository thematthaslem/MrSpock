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
  <div class="all-content serp">                         <?php
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
        <input id="search_input" type="text" name="search" placeholder="Explore new articles..." value="<?php echo $search;?>"/>
        <div class="mic-wrap">
          <img id="microphone"  src="//i.imgur.com/cHidSVu.gif" />
        </div>

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
      <div class="results-wrap">    <?php
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
                           'minimum_should_match' => '90%'
                           //'operator' => 'and'
                           //'fuzziness' => '2'
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
<!-- <h3 class="txt-center">More results:</h3>-->
<div class="pagination-wrap">
  <?php

    /*
      Build the href for each page button
        - Need to take the full url and get the query part
        - create a new string with everything in the query, removing the old 'page' param
        - add new page value
    */
    $curr_url = $_SERVER['QUERY_STRING'];
    parse_str($curr_url, $query_params);

    /*
      Display pages links
    */

    // If number of pages is greater than 14, set it so it only shows 14 pages and a last
    $counter = 0;

    // Check if these numbers is a part of new set (1-14 is a set; 14-28 is a set;)
    //   We just need the first number in set
    //   - To find first of set we need to do (curr_page_number - mod13 + 1)
    $start_of_set = $curr_page_number - ($curr_page_number % 13);
    if($start_of_set == 0){$start_of_set = 1;}

    for($i=$start_of_set; $i<=$number_of_pages; $i++)
    {
      // Set page number for current item
      $page_number = $i;



      //If there are 14 pages already => show last page button. Set to last page
      //if($counter > 13)
      if($counter > 3 && ($page_number % 13) == 2)
      {
        $page_number = $number_of_pages;
        $i = $number_of_pages; // This sets it as last page
        echo ' <div style="margin-right:8px;">...</div>';
      }

      // change in array. (It's minus one because the 1st page should show result 0. 2nd page show 10)
      $query_params['page'] = $page_number - 1;
      // Put array in query to attach to href.
      $page_query = http_build_query($query_params, '&');

      // If it's on the current page, make the button unclickable. (give it the 'active' class)
      $is_active = "";
      if($page_number - 1 == $curr_page_number)
      {
        $is_active = "active";
      }

  ?>

    <a
      class="pagination-item <?php echo $is_active; ?>"
      href="serp.php?<?php echo $page_query;?>"
    >
      <?php echo $i;?>
    </a>
  <?php
    $counter++;
    }
  ?>
</div>

        <!--h1 Results-->
        <!--h2 # of Results-->
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