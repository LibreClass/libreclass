$(document).ready(function(){

  $('#modalPeriods').on('hidden.bs.modal', function () {
   location.reload();
  });

  $("#new-course").click(function(){
    $("#form-course input[name='course']").val("");
    $("#form-course input[name='name']").val("");
//      $("#form-course input[name='ementa']").val(data.ementa);
    $("#form-course input[name='absent_percent']").val("25.0");
    $("#form-course input[name='average']").val("7.00");
    $("#form-course input[name='average_final']").val("5.00");
    $("#modalCourse").modal();
  });

  $("#new-periods").click(function(){
    $("#modalPeriods").modal();
    $("#add-periods select[name='course']").change();
  });

  $("#new-block").click(function(){
    $("#title-course").text("Novo Curso");
    $("#add-course input[name='course']").val("");
  });

  $(".course-edit").click(function(){
    var course = $(this).attr("key");
    $("#form-course input[name='course']").val(course);
    $("#modalCourseLabel").html("<b>Editar Curso</b>");
    $("#modalCourse").modal();

    $.getJSON("/courses/edit", {
      "course": course
    }, function(data){
      $("#form-course input[name='name']").val(data.name);
      $("#form-course input[name='type']").val(data.type);
      $("#form-course input[name='modality']").val(data.modality);
      $("#form-course input[name='quant_unit']").val(data.quant_unit);
//      $("#form-course input[name='ementa']").val(data.ementa);
      $("#form-course input[name='absent_percent']").val(data.absent_percent);
      $("#form-course input[name='average']").val(data.average);
      $("#form-course input[name='average_final']").val(data.average_final);
      $("#block-add").fadeIn();
    });
  });

  $("#add-periods select[name='course']").change(function(){
    $("#list-periods").html("");
    $.post("/courses/period", {
      course: $(this).val()
    }, function(data){
      for( var i = 0 ; i < data.length ; i++ )
        $("#list-periods").append($("<div>", {
          class: "row key",
          key: data[i].id,
          html: $("<form>", {
              class: "col-xs-4 input-edit-period",
              append: $("<input>", {
                class: "form-control input-border-none",
                name: "period",
                value: data[i].name
              })
            })/*,
          append: $("<div>", {
            class:"col-xs-2",
            append: $("<i>", {
              class: "fa fa-trash text-muted"
            })
          })*/
        }));

      $(".input-edit-period").unbind("submit").submit(function(){
        var data = {
          value: $(this).find("input[name='period']").val(),
          course: $("#add-periods select[name='course']").val()
        };

        if ($(this).closest(".key").is("[key]"))
          data.key= $(this).closest(".key").attr("key");
        else
          $("#input-new-period").val("");


        $.post("/courses/editperiod", data, function (data) {
          if( data == "ok" )
            $("#add-periods select[name='course']").change();
          else
            alert("Ocorreu algum erro. Consulte o suporte.");
        });
        return false;
      });
      $("#input-new-period").focus();
    });

  });
});
