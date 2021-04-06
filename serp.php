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
  <div class="all-content serp">                       <?php
  /*
      Get search info
  */
  $search = filter_var($_GET['search'], FILTER_SANITIZE_STRING);
  $author = filter_var($_GET['author'], FILTER_SANITIZE_STRING);
  $publisher = filter_var($_GET['publisher'], FILTER_SANITIZE_STRING);


?>

<div class="top-bar">
  <a href="index.php">
    <div class="logo-wrap"><img src="_pics/logo.png" alt="Mr. Spock Logo"/></div>
  </a>
  <div class="search-wrap-all">
    <form>
      <div class="search-wrap">
        <input type="text" name="search" placeholder="Explore new articles..." value="<?php echo $search;?>"/>
        <button type="submit"><img src="_pics/search_arrow.svg" alt="search arrow"/></button>
      </div>
      <div class="advanced-search-wrap">
        <div class="link"><span class="open-advanced">Advanced Search</span></div>
        <div class="advanced-search-items">
          <div class="items-wrap">
            <label for="author-input">Author:</label>
            <input type="text" id="author-input" name="author" value="<?php echo $author; ?>"/>
            <label for="publisher-input">Publisher:</label>
            <input type="text" id="publisher-input" name="publisher" value="<?php echo $publisher; ?>"/>
          </div>
        </div>
      </div>
    </form>
  </div>

  <div class="nav-wrap">
    <a class="link" href="add-document.php">+ Add New Document</a>
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
      <div class="results-wrap">  <?php
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
                           'query'     => $search
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
                                 'query'     => $search
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
      <div class="title"><a href="page.php?id=<?php echo $item_id; ?>"><?php echo $data['title'];?></a></div>
      <div class="authors">Authors: <?php echo $data['contributor_author'];?></div>
      <div class="publishers">Publisher: <?php echo $data['publisher'];?></div>
      <div class="desc">
          <?php
            if( !empty($data['description_abstract']) )
            {
              echo substr($data['description_abstract'],0,550);
            }
          ?>
          ( <a href="page.php?id=<?php echo $item_id; ?>">Read More</a> )
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

    for($i=1; $i<=$number_of_pages; $i++)
    {
      // Set page number for current item
      $page_number = $i;
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