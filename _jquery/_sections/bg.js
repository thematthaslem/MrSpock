////////////////////////////////
/// FLIP TOP //////////////////
///////////////////////////////

var menuLink = $('.menu-link > a');
var topSide = $('.top-side');
menuLink.on('click', function(){
  topSide.toggleClass('flip');
});

var windowW, redPosY, redPosY2; // redPosY2 is for bottom bar

$(document).mousemove(function(event){
    windowW = $(window).width();

    redPosX = event.pageX - (windowW/2);
    redPosY = event.pageY + 100;
    redPosY2 = event.pageY + 100 - $(window).height();

    $('.red-ball.top-ball').css({
      "left": event.pageX + "px",
      "top": redPosY + "px"
    });
    $('.red-ball.bottom-ball').css({
      "left": event.pageX + "px",
      "top": redPosY2 + "px"
    });
});



/*
var square;
$('.square').on({
  mouseenter: function(){
        square = $(this);
        $(this).addClass('flip');
        console.log('enter');
        setTimeout(function(){
            square.removeClass('flip');
            console.log('leave');
        }, 500);
  },
  mouseleave: function(){
    square = $(this);

  }
});

console.log('damn');
*/
