$(function (){
  $("#pagination a").click(function(){
    $("#find-student input[name='current']").val($(this).attr("href"));
    $("#find-student").submit();
    return false;
  });

  $(".new-student").click(function(){
    $(".block-search-student").hide();
    $(".block-new-student").show();
  });
  
  $("#search-student").submit(function(){
    alert();
    $(this).nextAll(".result").load("/user/find-user/"+encodeURI($(this).find("input:text").val()),
    link).show();
    return false;
  });

  $("#submit-student").click(function(){
    $("#find-student input[name='current']").val("0");
  });

  $( "#target" ).keypress(function( event ) {
    if ( event.which == 13 )
      $("#find-student input[name='current']").val("0");
  });


  $( "#btnLinkStudent" ).click(function() {
    $( "#modalLinkStudentClasse" ).modal();
  });

  $("#btnCertificate").unbind("click").click(function() {
    $("#modalCertificate").modal();
  });

  $("#btnInvite").unbind("click").click(function() {
    $("#modalInvite").modal();
  });

  $("#classe").change(function(){
    $.post("/classes/list-offers",{
      class: $(this).val(),
      student: $("#formLinkStudentClasse input[name='student']").val()
    }, function(data){
      console.log(data);
      $("#list-offers").html("");
      for (var i = 0 ; i < data.length ; i++)
        $("#list-offers").append($("<input>", {
          type: "checkbox",
          name: "offers[]",
          value: data[i].id,
          checked: data[i].status > 0,
          disabled: data[i].status > 0
        })).append(data[i].name).append("<br>");
    }).fail(function(){alert("error");});
  }).change();

  $("#class-change").change(function(){
    $("#class-list").html($("<div>",{
      class: "text-center spinner",
      html: $("<i>", {
        class:"fa fa-spin fa-lg fa-spinner"
      })
    }));
    $("#class-list").load("/user/reporter-student-class", {
      class: $(this).val(),
      student: $("#formLinkStudentClasse input[name='student']").val()
    }, function(data){
     $(".panel-history").click(function(){
       $(this).next(".panel-body").slideToggle("slow");
     });
    });
  }).change();
	
	$(".add-censo").click(function() {
//		alert($("input[name='enrollment']").);
//		alert($("input[name='enrollment']").val());
//		
		location.href = "/censo/student";
		if($("input[name='enrollment']").val() && $("input[name='name']").val()) {
	
			$.confirm("Clique em Sim para salvar o novo aluno e seguir para adicionar as informações do censo.", function() {
				$.ajax({
					url: location.href,
					type: 'GET',
					data: $("#new-student").serialize(),
					success: function(data) {
						location.href = "/censo/student?id="+data.id;
					},
					error: function(e) {
						$.alert("Erro", "Erro ao redirecionar. Tente novamente mais tarde.", 1);
					}
				});
			});
		}
		else {
			$.alert("Erro", "Campos obrigatórios não preenchidos", 1);
		}
		return false;
	});
	
});
