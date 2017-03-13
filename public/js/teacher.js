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

  $("#new-teacher").click(function(){
    $("#formAddTeacher input[name='teacher']").val("");
    $("#formAddTeacher input[name='enrollment']").val("");
    $("#formAddTeacher input[name='name']").val("");
    $("#formAddTeacher input[name='formation']").val("");
    $("#modalAddTeacher").modal();
  });

  $(".edit-teacher").click(function(){
    var teacher = $(this).closest(".data").attr("data");
    //alert(teacher);
    $.getJSON("/user/infouser", {
      "user": teacher
    }).done(function(data) {
      $("#formAddTeacher input[name='teacher']").val(teacher);
      $("#formAddTeacher input[name='enrollment']").val(data.enrollment);
      $("#formAddTeacher input[name='name']").val(data.name);
      $("#formAddTeacher input[name='formation']").val(data.formation);
      $("#modalAddTeacher").modal();
    }).fail(function() {
      $("#modalAlert .modal-body").html("<span class='text-center'>Não foi possível realizar a operação. Se o problema persistir contate o <a href='mailto:suporte@sysvale.com'>suporte</a></span>");
      $("#modalAlert").modal();
    });
  });

  $("#modal-body", "#modalAddTeacherDiscipline").load("/bind/list", {
    teacher: $("#modalAddTeacherDiscipline").attr("teacher")
  }, function(){
    $("#modalAddTeacherDiscipline input:checkbox").click(function(){
      $.post("/bind/link", {
        user: $("#modalAddTeacherDiscipline").attr("teacher"),
        discipline: $(this).attr("value"),
        bind: $(this).is(":checked")
      });
    });
    $("#modalAddTeacherDiscipline #search").keyup(function(){
      var search = $(this).val().toLowerCase();
      checkbox = $("#modalAddTeacherDiscipline .checkbox");
      for( i = 0 ; i < $(checkbox).length ; i++ ) {
        text = $(checkbox).eq(i).text().toLowerCase();
        if(text.indexOf(search) != -1)
          $(checkbox).eq(i).show();
        else
          $(checkbox).eq(i).hide();
      }
    })
  });

  $(".add-teacher-discipline").click(function() {
    $("#modalAddTeacherDiscipline").modal();
  });

  $("#modalAddTeacherDiscipline").on("hide.bs.modal", function(){
    location.reload();
  });

});
