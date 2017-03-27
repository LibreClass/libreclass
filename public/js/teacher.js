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
    $("#modalAddTeacher input[name='email']").val('');
    $("#modalAddTeacher input[name='enrollment']").prop('disabled', false).val('');
    $("#modalAddTeacher input[name='name']").prop('disabled', false).val('');
    $("#modalAddTeacher select[name='formation']").prop('disabled', false).val('');
    $("#modalAddTeacher .teacher-message").hide();
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

  var timeout = null;
  $("#modalAddTeacher .spinner").hide();
  $("#modalAddTeacher .teacher-message").hide();

  $("#modalAddTeacher input[name='email']").keyup(function() {
    $("#modalAddTeacher .spinner").show();
    clearTimeout(timeout);
    timeout = setTimeout(function() {
      searchEmail($("#modalAddTeacher input[name='email']").val());
    }.bind(this), 1000);
  }.bind(this));

  function searchEmail(str) {
    $("#modalAddTeacher .spinner").hide();
    $("#modalAddTeacher .teacher-message").hide();
    $("#modalAddTeacher button[type='submit']").prop('disabled', false);
    $("#modalAddTeacher input[name='enrollment']").prop('disabled', false);
    $.post('/user/search-teacher', { str: str }, function(data) {
      if (data.status == 1) {
        $("#modalAddTeacher input[name='name']").val(data.teacher.name).prop('disabled', 'disabled');
        $("#modalAddTeacher select[name='formation']").val(data.teacher.formation).prop('disabled', 'disabled');
        $("#modalAddTeacher input[name='teacher']").val(data.teacher.id);
        $("#modalAddTeacher input[name='registered']").val('true');
        $("#modalAddTeacher .teacher-message").html('<b>' + data.message + '</b>').show();
      } else if (data.status == 0) {
        $("#modalAddTeacher input[name='name']").val('').prop('disabled', false);
        $("#modalAddTeacher select[name='formation']").val('').prop('disabled', false);
        $("#modalAddTeacher input[name='teacher']").val('');
        $("#modalAddTeacher input[name='registered']").val('');
        $("#modalAddTeacher .teacher-message").hide();
      } else if (data.status == -1) {
        $("#modalAddTeacher input[name='enrollment']").val(data.teacher.enrollment).prop('disabled', 'disabled');
        $("#modalAddTeacher input[name='name']").val(data.teacher.name).prop('disabled', 'disabled');
        $("#modalAddTeacher select[name='formation']").val(data.teacher.formation).prop('disabled', 'disabled');
        $("#modalAddTeacher button[type='submit']").prop('disabled', 'disable');
        $("#modalAddTeacher .teacher-message").html('<b>' + data.message + '</b>').show();
      }
    });
  }

});
