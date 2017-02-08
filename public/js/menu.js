$(function(){
  $(".menu-icon").click(function(){
    //alert("opa");
    $(".cortina").fadeIn();
    $(".menu-slider").addClass("menu-ativo");
    $('body').css('overflow', 'hidden');
   
  });
  
  $(".cortina").click(function(){
    $(".menu-slider").removeClass("menu-ativo"); 
    $(".cortina").fadeOut();
    $('body').removeAttr('style');
  });
  
  $(".scroll a").click(function(event){
      
    
    if(this.hash) {
      event.preventDefault();
      $('html,body').animate({scrollTop:$(this.hash).offset().top-40}, 900);
      $(".cortina").click();
    }
  });
//  $('a').bind('click',function(event){
//    alert('opa');
//    var $anchor = $(this);
//
//    $('html, body').stop().animate({
//      scrollTop: $($anchor.attr('href')).offset().top}, 1000,'easeInOutExpo');
//
//// Outras Animações
//// linear, swing, jswing, easeInQuad, easeInCubic, easeInQuart, easeInQuint, easeInSine, easeInExpo, easeInCirc, easeInElastic, easeInBack, easeInBounce, easeOutQuad, easeOutCubic, easeOutQuart, easeOutQuint, easeOutSine, easeOutExpo, easeOutCirc, easeOutElastic, easeOutBack, easeOutBounce, easeInOutQuad, easeInOutCubic, easeInOutQuart, easeInOutQuint, easeInOutSine, easeInOutExpo, easeInOutCirc, easeInOutElastic, easeInOutBack, easeInOutBounce
//                  
//
//  });;
});

