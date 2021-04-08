// Open advanced-search-items wrap on click of .open-advanced
$('.open-advanced').on('click',
  function(e)
  {
    $(this).parent().next('.advanced-search-items').toggleClass('open');
  }
);

// Closes if clicked somewhere else
$('body').click(function(evt){
       if($(evt.target).hasClass('advanced-search-items') || $(evt.target).hasClass('open-advanced') || $(evt.target).hasClass('open-advanced-link'))
       {
         return;
       }

       //For descendants of menu_content being clicked, remove this check if you do not want to put constraint on descendants.
       if($(evt.target).closest('.advanced-search-items').length)
          return;

      //Do processing of click event here for every element except with id menu_content
      $('.advanced-search-items').removeClass('open');
      console.log('nice' + $(evt.target).attr('class'));
});
