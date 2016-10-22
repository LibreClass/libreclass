$(function() {
  $("#btn-offer-discipline").click(function() {
    $("#block-offer-discipline").toggleClass("visible-none");
  });

  $("#btn-offer-users").click(function() {
    $("#block-offer-users").toggleClass("visible-none");
  });

  $("#period").change(function() {

    $("#block-offer-discipline").load("classes/listdisciplines", {
      "period": $(this).val()
    }, function() {});

  }).change();

  $(".classe-edit").click(function() {
    var classe = $(this).closest(".data").attr("key");
    $("#form-classe input[name='discipline']").val(classe);
    $("#block").hide();
    $("#title-classe").html("<b>Editar Turma</b>");
    alert(classe);

    $.getJSON("/classes/edit", {
      "classe": classe
    }, function(data) {
      alert(data);
      $("#form-classe input[name='class']").val(data.class);
      $("#form-classe input[name='period']").val(data.period);

      $("#block-add").fadeIn();
    });
  });

});
