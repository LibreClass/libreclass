
$(function() {
  $("#btn-offer-discipline").click(function(){
    $("#block-offer-discipline").toggleClass("visible-none");
  });

  $("#btn-offer-users").click(function(){
    $("#block-offer-users").toggleClass("visible-none");
  });

  $("#period").change(function(){

    $("#block-offer-discipline").load("classes/listdisciplines", {
      "period": $(this).val()
    }, function(){
    });

  }).change();

  $(".classe-edit").click(function(){
    var classe = $(this).closest(".data").attr("key");

    $.getJSON("/classes/info", {
      "classe": classe
    }, function(data){
      $("#formEditClass input[name='classId']").val(classe);
      $("#formEditClass input[name='class']").val(data.name);

			$("#modalEditClass .EditClass-list-disciplines").load("classes/listdisciplines", {
	      "period": data.idPeriodCrypt,
	      "flag": 1
	    }, function(){
	    });

      $("#modalEditClass").modal();
    });
  });

  $("#new-class").click(function(){
    $("#formAddClass input[name='class']").val("");
    $("#modalAddClass").modal();
  });

	/*Bloquear unidades - Solicita Json e abre o modal*/
  $("#block-unit").click(function() {
    $.post("/classes/list-units/1", {})
    .done(function(data) {
      $("#formBlockUnit select[name='course']").html("");
      $.each(data, function (i, item) {
        $("#formBlockUnit select[name='course']").append($('<option>', {
            value: JSON.stringify([item.id, item.units]),
            text : item.name
        }));
      });
      $("#formBlockUnit select[name='course']").change();
      $("#modalBlockUnit").modal();
    })
    .fail(function(){

    });
    $("#modalBlockUnit").modal();
  });

	/*Bloquear unidades - Interpreta os dados e lista no select*/
  $("#formBlockUnit select[name='course']").change(function(){
    var course = JSON.parse($(this).val());

    $("#formBlockUnit #unit").html("");
    $.each(course[1], function (i, item) {
      $("#formBlockUnit #unit").append($('<option>', {
          value: item.value,
          text : "Unidade " + item.value
      }));
    });
  });

	/*Bloquear unidades - Submete o formulário*/
  $("#formBlockUnit").submit(function(){
    if ( !($("#formBlockUnit #unit").val()) ){
      $.alert("Erro", "Selecione uma unidade.", true);
      $("#modalBlockUnit .close").click();
    }
    else
    {
      $("#formBlockUnit .form-submit").hide();
      $("#formBlockUnit .form-process").show();
      var course = JSON.parse($("#formBlockUnit select[name='course']").val());
      $.post("/classes/block-unit", {
        unit: $("#formBlockUnit #unit").val(),
        course: course[0]
      }).done(function(data) {
        $.alert("Sucesso", "Unidade bloqueada com sucesso.");
        $("#modalBlockUnit .close").click();
        $("#formBlockUnit .form-submit").show();
        $("#formBlockUnit .form-process").hide();
      }).fail(function(){
        $.alert("Erro", "Erro inesperado. Informe ao suporte.", true);
        $("#modalBlockUnit .close").click();
        $("#formBlockUnit .form-submit").show();
        $("#formBlockUnit .form-process").hide();
      });
    }

    return false;
  });

	/*Desbloquear unidades - Solicita Json e abre o modal*/
	$("#unblock-unit").click(function() {
		alert();
		$.post("/classes/list-units/0", {})
			.done(function(data) {
			$("#formUnblockUnit select[name='course']").html("");
			$.each(data, function (i, item) {
				$("#formUnblockUnit select[name='course']").append($('<option>', {
					value: JSON.stringify([item.id, item.units]),
					text : item.name
				}));
			});
			$("#formUnblockUnit select[name='course']").change();
			$("#modalUnblockUnit").modal();
		})
			.fail(function(){

		});
		$("#modalunblockUnit").modal();
	});

	/*Desbloquear unidades - Interpreta os dados e lista no select*/
	$("#formUnblockUnit select[name='course']").change(function(){
		var course = JSON.parse($(this).val());

		$("#formUnblockUnit #unit").html("");
		$.each(course[1], function (i, item) {
			$("#formUnblockUnit #unit").append($('<option>', {
				value: item.value,
				text : "Unidade " + item.value
			}));
		});
	});

	/*Desbloquear unidades - Submete o formulário*/
	$("#formUnblockUnit").submit(function(){
		if ( !($("#formUnblockUnit #unit").val()) ){
			$.alert("Erro", "Selecione uma unidade.", true);
			$("#modalUnblockUnit .close").click();
		}
		else
		{
			$("#formUnblockUnit .form-submit").hide();
			$("#formUnblockUnit .form-process").show();
			var course = JSON.parse($("#formUnblockUnit select[name='course']").val());
			$.post("/classes/unblock-unit", {
				unit: $("#formUnblockUnit #unit").val(),
				course: course[0]
			}).done(function(data) {
				$.alert("Sucesso", "Unidade desbloqueada com sucesso.");
				$("#modalUnblockUnit .close").click();
				$("#formUnblockUnit .form-submit").show();
				$("#formUnblockUnit .form-process").hide();
			}).fail(function(){
				$.alert("Erro", "Erro inesperado. Informe ao suporte.", true);
				$("#modalUnblockUnit .close").click();
				$("#formUnblockUnit .form-submit").show();
				$("#formUnblockUnit .form-process").hide();
			});
		}

		return false;
	});
	/*-------------------------------------------------------------------------*/

  $("#create-unit").click(function() {
    $.post("/courses/all-courses", {})
      .done(function(data) {
        console.log(data);
        $.each(data, function (i, item) {
        $("#formCreateUnit select[name='course']").append($('<option>', {
            value: item.id,
            text : item.name
        }));
      });
    });
    $("#formCreateUnit select[name='course']").change();
    $("#modalCreateUnit").find(".form-process").hide();
    $("#modalCreateUnit").find(".form-submit").show();
    $("#modalCreateUnit").modal();
  });

  $("#formCreateUnit").submit(function(){
    $(this).find(".form-process").show();
    $(this).find(".form-submit").hide();
    $.post($(this).attr("action"), {
      course: $(this).find("[name='course']").val()
    }, function(data){
      $("#modalCreateUnit").modal("hide");
      $("#modalAlert .modal-title").text("Sucesso");
      $("#modalAlert .modal-body").text("As unidades foram criadas com sucesso!");
      $("#modalAlert").modal();
    }).fail(function(XMLHttpRequest, textStatus, errorThrown){
      console.log([XMLHttpRequest, textStatus, errorThrown]);
      error = JSON.parse(XMLHttpRequest.responseText);
      $("#modalCreateUnit").modal("hide");
      $("#modalAlert .modal-title").text("Erro");
      $("#modalAlert .modal-body").html(error.error.message);
      $("#modalAlert").modal();
    });
    return false;
  });

	/*Delelar Unidade*/

	$("#delete-unit").click(function() {
		$.post("/courses/all-courses", {})
			.done(function(data) {
			console.log(data);
			$.each(data, function (i, item) {
				$("#formDeleteUnit select[name='course']").append($('<option>', {
					value: item.id,
					text : item.name
				}));
			});
		});
		$("#formDeleteUnit select[name='course']").change();
		$("#modalDeleteUnit").find(".form-process").hide();
		$("#modalDeleteUnit").find(".form-submit").show();
		$("#modalDeleteUnit").modal();
	});

	$("#formDeleteUnit").submit(function(){
		$(this).find(".form-process").show();
		$(this).find(".form-submit").hide();
		$.post($(this).attr("action"), {
			course: $(this).find("[name='course']").val()
		}, function(data){
			$("#modalDeleteUnit").modal("hide");
			$("#modalAlert .modal-title").text("Sucesso");
			$("#modalAlert .modal-body").text("As unidades foram deletadas com sucesso!");
			$("#modalAlert").modal();
		}).fail(function(XMLHttpRequest, textStatus, errorThrown){
			console.log([XMLHttpRequest, textStatus, errorThrown]);
			error = JSON.parse(XMLHttpRequest.responseText);
			$("#modalDeleteUnit").modal("hide");
			$("#modalAlert .modal-title").text("Erro");
			$("#modalAlert .modal-body").html(error.error.message);
			$("#modalAlert").modal();
		});
		return false;
	});

});
