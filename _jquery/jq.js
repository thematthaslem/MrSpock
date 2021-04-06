$(document).ready(function(){// Open advanced-search-items wrap on click of .open-advanced
$('.open-advanced').on('click',
  function(e)
  {
    $(this).parent().next('.advanced-search-items').toggleClass('open');
  }
);
var backButton = $('.go-back-button');
backButton.on('click', function(){
  window.history.back();
});
console.log('Hi Professor!');
});             