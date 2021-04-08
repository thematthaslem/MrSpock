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

    for($i=1; $i<=$number_of_pages; $i++)
    {
      // Set page number for current item
      $page_number = $i;

      //If there are 14 pages already => show last page button. Set to last page
      if($counter > 13)
      {
        $page_number = $number_of_pages;
        $i = $number_of_pages;
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
