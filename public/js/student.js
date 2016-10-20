$(function (){
  $("#pagination a").click(function(){
    $("#find-student input[name='current']").val($(this).attr("href"));
    $("#find-student").submit();
    return false;
  });
//
  $("#submit-student").click(function(){
    $("#find-student input[name='current']").val("0");
  });

  $( "#target" ).keypress(function( event ) {
    if ( event.which == 13 )
      $("#find-student input[name='current']").val("0");
  });

});