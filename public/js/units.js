$(function (){

  $("#form-calculation").submit(false);
  $("#form-calculation #calculation").change(function(){
    $.post("/lectures/units/edit", {
      "calculation": $(this).val(),
      "u": $("#form-calculation input[name='unit']").val()
    }, function(data){
      msg = "Erro ao modificar.";
      if(data)
        msg = "Modificado com sucesso.";
      $("#message").html(msg).show();
    });
  });
});
