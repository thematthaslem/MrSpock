// Open advanced-search-items wrap on click of .open-advanced
$('.open-advanced').on('click',
  function(e)
  {
    $(this).parent().next('.advanced-search-items').toggleClass('open');
  }
);
