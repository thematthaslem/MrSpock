<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/elastic.php';
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
    Get search info
*/

// Search Query
if(isset($_GET['search']))
{
  $search = filter_var($_GET['search'], FILTER_SANITIZE_STRING);
}
else {$search = "";}

// Search ID
if(isset($_GET['id']))
{
  $art_id = filter_var($_GET['id'], FILTER_SANITIZE_STRING);
}
else {$art_id = "";}

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


if(isset($art_id) && $art_id != "")
{
  $params = [
    'index' => 'test_index',
    'id' => $art_id
  ];
  $response = $client->get($params);

  $data = $response['_source'];
}
else
{
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

                  'should' => [
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

   $response = $client->search($params);
   $item_count = $response['hits']['total']['value'];
   $items = $response['hits']['hits'];

   $data = array();

   foreach ($items as $item) {
     $data[] = $item['_source'];
   }
}




if(!empty($data))
{
  // set response code - 200 OK
  http_response_code(200);

  // show products data in json format
  $results_arr = array('yo' => 'shibby' );
  echo json_encode($data);
}
else
{
  // show products data in json format
  $results_arr = array('yo2' => 'shibby2' );
  echo json_encode($results_arr);
}
/*
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

// initialize object
$product = new Product($db);

// query products
$stmt = $product->read();
$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0){

    // products array
    $products_arr=array();
    $products_arr["records"]=array();

    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);

        $product_item=array(
            "id" => $id,
            "name" => $name,
            "description" => html_entity_decode($description),
            "price" => $price,
            "category_id" => $category_id,
            "category_name" => $category_name
        );

        array_push($products_arr["records"], $product_item);
    }

    // set response code - 200 OK
    http_response_code(200);

    // show products data in json format
    echo json_encode($products_arr);
}

else{

    // set response code - 404 Not found
    http_response_code(404);

    // tell the user no products found
    echo json_encode(
        array("message" => "No products found.")
    );
}
*/
