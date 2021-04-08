<?php
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }
  // Init ES
  require '../vendor/autoload.php';
  $client = Elasticsearch\ClientBuilder::create()->build();

  // init items
  $title = $_POST['title'];
  $contributor_author = $_POST['contributor_author'];
  $description_abstract = $_POST['description_abstract'];
  $contributor_committeechair = $_POST['contributor_committeechair'];
  $contributor_committeemember = explode (",", $_POST['contributor_committeemember']); // array
  $contributor_department = $_POST['contributor_department'];
  $date = $_POST['date'];
  $degree_grantor = $_POST['degree_grantor'];
  $degree_level = $_POST['degree_level'];
  $description_abstract = $_POST['description_abstract'];
  $description_degree = $_POST['description_degree'];
  $description_provenance = explode (",", $_POST['description_provenance']); // array
  $subject = explode (",", $_POST['subject']); // array
  $identifier_sourceurl = $_POST['identifier_sourceurl'];
  $publisher = $_POST['publisher'];
  //$relation_haspart = $_POST['relation_haspart']; // FILE
  $relation_haspart = $_FILES["relation_haspart"];

  // In case of errors
  $error_arr = array();

  // For success messages
  $success_arr = array();

  /*
    Calculate handle
      - Just look at the last directory in dissertations dir and add one
  */

  $files_list = scandir('../_files_dissertation/', SCANDIR_SORT_DESCENDING);
  $file_count = count($files_list) - 3;
  $last_file = $files_list[0];
  $new_handle = $last_file + 1;


  /*
    Upload File
  */

  // Create new directory for the new handle
  if(mkdir('../_files_dissertation/' . $new_handle))
  {
    $success_arr[] = "Folder $new_handle created successfully.";
  }
  else
  {
    $error_arr[] = "Folder $new_handle could not be created.";
  }

  // Target new directory
  $target_dir = "../_files_dissertation/$new_handle/";
  $target_folder = $target_dir . basename($relation_haspart["name"]);

  // Make sure it's a pdf
  //  - if not => add to error array
  $target_file_type = strtolower(pathinfo($target_folder,PATHINFO_EXTENSION));
  if($target_file_type !== 'pdf' && $target_file_type !== 'PDF')
  {
    $error_arr[] = "File must be a pdf.";
  }

  // Upload file
  if(move_uploaded_file($relation_haspart['tmp_name'], $target_folder))
  {
    $success_arr[] = $relation_haspart["name"] . " uploaded successfully.";
  }
  else
  {
    $error_arr[] = "There's been an error, Captain. File could not be uploaded";
  }


  /*
    Index Item
  */
  $params = [
      'index'     => 'test_index',
      'body'      => [
        'title' => $title,
        'contributor_author' => $contributor_author,
        'contributor_committeechair' => $contributor_committeechair,
        'contributor_committeemember' => $contributor_committeemember,
        'contributor_department' => $contributor_department,
        'date_issued' => $date,
        'degree_grantor' => $degree_grantor,
        'description_abstract' => $description_abstract,
        'description_degree' => $description_degree,
        'description_provenance' => $description_provenance,
        'subject' => $subject,
        'identifier_sourceurl' => $identifier_sourceurl,
        'relation_haspart' => $relation_haspart["name"],
        'publisher' => $publisher,
        'handle' => $new_handle,
        'degree_level' => $degree_level

      ]
  ];
  $response = $client->index($params);

  // If result is good => print to success
  if($response['result'] == "created")
  {
    $document_id = $response['_id'];
    $success_arr[] = "Success! Your document has been created. <a target=\"_blank\" class=\"link\" href=\"page.php?id=$document_id\">Click Here</a> to view it.";
  }
  else
  {
    $error_arr[] = "Something went wrong. Your document could not be created.";
  }

  /*
    Send back to page with success_arr or error_arr
  */
  if ( !empty($error_arr) )
  {
    $_SESSION['error'] = $error_arr;
    header('Location: ../add-document.php');
  }
  else
  {
    $_SESSION['success'] = $success_arr;
    header('Location: ../add-document.php');
  }


  echo $title;
  echo "<h1>Response:</h1>";
  print_r($response);


  /*
    Show success message with link to document
  */

  echo "<h1>ERRORS:</h1>";
  print_r($error_arr);
  echo "<h1>SUCCESSES:</h1>";
  print_r($success_arr);

?>
