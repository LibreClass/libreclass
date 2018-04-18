$(function(){
  $(".view-syllabus").click(function(){
    //$(this).closest("div").next("div").find(".syllabus").toggleClass("visible-none");
   var discipline = $(this).attr("key");

    $.getJSON("/disciplines/ementa", {
        "offer": discipline
    })
    .done(function(data){
      if(data.ementa != null) {
        $("#modalEmenta .modal-title").html(data.name);
        $("#modalEmentaBody .ementa").html("<p>"+ data.ementa +"</p>");
      }
      else {
        $("#modalEmentaBody .ementa").html("<p>Não possui ementa.</p>");
      }
    })
    .fail(function() {
      $("#modalEmenta .modal-header").addClass("modal-header-error");
      $("#modalEmenta .modal-title").html("Erro");
      $("#modalEmentaBody .ementa").html("<h4>Não foi possível localizar a ementa. Se o problema persistir contate o <a href='mailto:suporte@sysvale.com'>suporte</a></h4>");
    });


    $("#modalEmenta").modal();
  });

  $(".add-teacher").click(function(){
    var info = $(this).closest(".offer").find(".info");
     $("#formLinkingTeacher input[name=\'offer\']").val($(this).attr("key"));
    //  $("#formLinkingTeacher select[name=\'teacher\']").val($(info).attr("teacher"));
     $("#formLinkingTeacher input[name=\'classroom\']").val($(info).attr("classroom"));
     $("#formLinkingTeacher select[name=\'day_period\']").val($(info).attr("day_period"));
     $("#formLinkingTeacher input[name=\'maxlessons\']").val($(info).attr("maxlessons"));
    //  console.log($(info).attr("teacher")); return false;
     $("#formLinkingTeacher .main-sheriff .insert-sheriff li").not(".model").remove();
     $("#formLinkingTeacher .main-sheriff .selected").removeClass("selected");
     var teachers = JSON.parse($(info).attr("teacher"));
     for(var i = 0 ; i < teachers.length ; i++) {
       $("#formLinkingTeacher .list-sheriff li[data='"+teachers[i]+"']").click();
     }

     $("#modalTeacherOffer").modal();
  });

  $(".new-unit").click(function() {
    var url = $(this).attr('url');
    $.confirm("Tem certeza que deseja criar uma unidade para esta oferta? Essa operação é irreversível!", function() {
      document.location = url;
    });
    return false;
  });
	
	$(".delete-unit").click(function() {
		var url = $(this).attr('url');
		$.confirm("Esta ação irá deletar a última unidade da oferta. Essa operação é irreversível! Deseja continuar?", function() {
			document.location = url;
		});
		return false;
	});

  $("#find-teacher").sheriff("/user/teachers-friends/");
});
