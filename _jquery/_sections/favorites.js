/*
  ADD/REMOVE FAVORITES TO DATABASE
*/

var favButton;
var art_id, user, art_title, art_date, art_author;
var fav_type; // 'add' or 'remove'
$('.favorite-button').on('click', function(e){
  favButton = $(this);

  art_id = $(this).attr('data-id');
  user = $(this).attr('data-user');
  art_title = $(this).attr('data-title');
  art_date = $(this).attr('data-date');
  art_author = $(this).attr('data-author');

  // If it already has class 'selected' or has data-type set to 'remove' -> that means we want to remove
  if($(this).hasClass('selected'))
  {
    fav_type = 'remove';
  }
  else
  {
    fav_type = 'add';
  }

  // Send ajax request
  $.ajax(
    {
      url: "_php/update_favorites.php",
      type: 'POST',
      data: {
        art_id: art_id,
        user: user,
        art_title: art_title,
        art_date: art_date,
        art_author: art_author,
        fav_type: fav_type
      }
    }
  ).done(
    function(results)
    {
      // Change the selected status of the button
      if(results == "success")
      {
        favButton.toggleClass('selected');

        // If the button has class 'remove-item' -> Remove the whole element it's in
        // - this is for the favorites_list.php which only has the remove button
        if( favButton.hasClass('remove-item') )
        {
          var itemWrapper = favButton.closest('.item');
          itemWrapper.remove();
        }

        // If the button has class 'page-button' -> Change text in button
        // - this is for the page.php. it goes from "Add to favorites" to "Remove from favorites"
        if( favButton.hasClass('page-button') )
        {
          var itemText = favButton.find('span');

          if( favButton.hasClass('selected') )
          {
            itemText.text('Remove From Favorites');
          }
          else
          {
            itemText.text('Add to Favorites');
          }

        }

      }
      else
      {
        alert('There was a problem with favoriting the item.');
      }



    }
  );

});
