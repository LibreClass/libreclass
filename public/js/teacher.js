$(function() {
  $(".invite-teacher").click(function() {
    var teacher = $(this).closest("ul").attr("data");
    $("#name-invite-teacher").text($(this).closest("ul").attr("data-name"));
    $("#form-invite-teacher input[name='teacher']").val(teacher);
    $("#invite-teacher-modal").modal();
  });
  
  $("#pagination a").click(function(){
    $("#find-teacher input[name='current']").val($(this).attr("href"));
    $("#find-teacher").submit();
    return false;
  });

  $("#submit-teacher").click(function(){
    $("#find-teacher input[name='current']").val("0");
  });

  $( "#target" ).keypress(function( event ) {
    if ( event.which == 13 )
      $("#find-teacher input[name='current']").val("0");
  });
  
  
});