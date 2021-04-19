$("a.button.dropdown").on('click', function(e){
  var targetClass = $(this).attr('data-target');
  $('.' + targetClass).toggleClass('open');
  $(this).toggleClass('open');
  console.log(targetClass);
});
