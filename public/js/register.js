$(function() {
  
  $("#btn-student, #btn-teacher").click(function() {
    $("#block-register").hide(); 
    $("#block-register-option").fadeIn(); 
  });
  
//  $("#btn-teacher").click(function() {
//    $("#block-register").hide(); 
//    $("#block-register-option").fadeIn();
//  });
  
  $("#btn-institution").click(function() {
    alert("ooa!"); 
  });
  
  $("#btn-register-facebook").click(function(){
      
  });
    
  $("#btn-register-google").click(function(){
      
  });
  
  $("#btn-register-email").click(function(){
    $("#block-register-option").hide();
    $("#block-register-email").fadeIn();
  });
  
  $(".register-back").click(function() {
    $("#block-register-email").hide();
    $("#block-register-option").fadeIn(); 
  });
  
  $(".has-feedback #eye-magic").mousedown(function() {
    $(this).prev("input[name='password']").attr("type", "text");
    $(this).removeClass("glyphicon-eye-open").addClass("glyphicon-eye-close");
  }).tooltip({
    title: 'Visualizar',
    placement: 'bottom'
  });
  
  $(document).mouseup(function(){
    $(".has-feedback input[name='password']").attr("type", "password");
    $(".has-feedback #eye-magic").addClass("glyphicon-eye-open").removeClass("glyphicon-eye-close");
  });
  
  $("#forgot-password").click(function(){
    $("#form-login").hide();
    $("#forgot-password-area").fadeIn();
  });
});
