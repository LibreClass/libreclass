$(document).ready(function() {

  $("#new-block").click(function() {
    $("#title-discipline").text("Nova Disciplina");
    $("#new-discipline input[name='discipline']").val("");
    $("#new-discipline input[name='course']").val("");
  });

  $("#select-course select").change(function() {
    //$("#new-discipline select[name='course']").val($(this).val());

    //~ alert("load start");
    $("#list-disciplines").load("/disciplines/list", {
      "course": $(this).val()
    }, function() {
      $(".trash").click(trash);
      //      $(".trash").click(function(){
      //        var key   = $(this).closest(".data").attr("key");
      //
      //        $("#form-trash input[name=\"input-trash\"]").val(key);
      //        $("#form-trash").attr("action", document.URL + "/delete");
      //
      //        $.confirm("Tem certeza que deseja excluir?", function(){
      //          $("#form-trash").submit();
      //        });
      //      });

      //   $(".discipline-trash").click(function(){
      //     var discipline = $(this).closest(".discipline").attr("discipline");
      //     $("#delete-discipline input[name='discipline']").val(discipline);
      //     $("#delete-discipline").submit();
      //   });

      $(".discipline-edit").click(function() {
        var discipline = $(this).closest(".data").attr("key");
        var periods = [],
          courses = [];
        $("#new-discipline input[name='discipline']").val(discipline);
        $("#block").hide();
        $("#title-discipline").text("Editar Disciplina");

        for (var i = 0; i < $("#new-discipline #period option").length; i++)
          periods[i] = $("#new-discipline #period option").eq(i).attr("value");

        for (var i = 0; i < $("#new-discipline #course option").length; i++)
          courses[i] = $("#new-discipline #course option").eq(i).attr("value");


        $.getJSON("/disciplines/edit", {
          "discipline": discipline,
          "periods": periods,
          "courses": courses
        }, function(data) {
          $("#new-discipline #course").val(data.course);
          $("#new-discipline select[name='period']").val(data.period);
          $("#new-discipline input[name='name']").val(data.name);
          $("#new-discipline #ementa").val(data.ementa);
          $("#block-add").fadeIn();
        });
      });

    });
  }).change();

  $("#new-discipline select[name='course']").change(function() {
    $.post("/disciplines/listperiods", {
      "course": $(this).val()
    }, function(data) {
      $("#new-discipline select[name='period']").html("");
      for (var i = 0; i < data.length; i++)
        $("#new-discipline select[name='period']").append($("<option>", {
          value: data[i].id,
          html: data[i].name
        }));
      console.log(data);

    });
  }).change();

});
