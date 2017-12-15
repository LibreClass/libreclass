
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
	      "period_id": data.idPeriodCrypt,
		  "classe_id": classe,
	      "flag": 1
	    }, function(){
	    });

      $("#modalEditClass").modal();
    });
  });

  $(".group").click(function(){
    var classe = $(this).closest(".data").attr("key");

    // $.getJSON("/classes/info", {
    //   "classe": classe
    // }, function(data){
    //   $("#formEditClass input[name='classId']").val(classe);
    //   $("#formEditClass input[name='class']").val(data.name);

    //   $("#modalEditClass .EditClass-list-disciplines").load("classes/listdisciplines", {
    //     "period_id": data.idPeriodCrypt,
    //   "classe_id": classe,
    //     "flag": 1
    //   }, function(){
    //   });

    //   $("#modalEditClass").modal();
    // });

    window.location.href = "/classes/group/" + classe;
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

	$("#view-classes [name='schoolYear']").change(function(e) {
		location.href="/classes?year="+$(e.target).val();
	});

	$(".progression", "#view-classes").click(function(e){
		var id = $(e.target).closest('tr.classe-item').attr('key');
		$.post('progression/students-and-classes', {'classe_id': id}, function(data) {
			var list = $("#modalProgressionClasses").find('.list-attends').empty();
			if(!data.status || !data.attends.length) {
				$.dialog.info('Erro', data.message);
				return;
			}
			else {
				var classes = '';
				data.next_classe.classes.forEach(function(item) {
					classes += '<option value="'+ item.id +'">'+ item.schoolYear +' - '+data.next_classe.name +' - '+ item.name +'</option>';
				});

				data.attends.forEach(function(item) {
					list.append(
					'<li class="list-group-item">'+
						'<fieldset>'+
							'<div class="row">'+
								'<div class="col-xs-9">'+
									'<div class="checkbox">'+
										'<label><input type="checkbox" name="progression_check">'+ item.user_name +
									'</div>'+
								'</div>'+
								'<div class="col-xs-3">'+
									'<select name="progression_type" class="form-control">'+
										'<option value="1">Progredir</option>'+
										'<option value="0" selected>Manter</option>'+
									'</select>'+
								'</div>'+
								// '<select name="classe_id" class="form-control">'+ classes +'</select>'+
									// '<input type="radio" name="type_progression"> Progressão parcial'+
								// '</div>'+
								// '<div class="col-xs-3">'+
									// '<input type="radio" name="type_progression"> Progressão integral'+
								// '</div>'+
							// '</div>'+
							'</div>'+
						'</fieldset>'+
					'</li>'
					);
				});
			}
			$("#modalProgressionClasses").modal();
		});
  });

	$("#receive-classes", "#view-classes").click(function(){
		var year = $("#view-classes [name='schoolYear']").val();
		var modal = $("#modalReceiveClass");
		var list = modal.find('.list-classes').empty();
		$.post('classes/classes-by-year', { 'year': year - 1 }, function(data) {
			if(data.classes.length) {
				var mapStatus = {
					'F': {status: 'Encerrada', label: 'label-default'},
					'B': {status: 'Bloqueada', label: 'label-danger'},
					'E': {status: 'Ativa', label: 'label-success'}
				};

				data.classes.forEach(function(classe) {
					list.append(
						'<li class="list-group-item">'+
							'<div class="checkbox checkbox-m-0">'+
								'<fieldset class="row">'+
									'<div class="col-xs-6">'+
										'<label><input type="checkbox" name="classe_id" value="'+ classe.id +'"> '+ classe.classe + ' - '+ classe.period +'</label>'+
									'</div>'+
									'<div class="col-xs-4">'+
										'<select name="with_offers" class="form-control input-xs">'+
											'<option value="true">Copiar com as ofertas</option>'+
											'<option value="false">Copiar sem as ofertas</option>'+
										'</select>'+
									'</div>'+
									'<div class="col-xs-2 text-right">'+
										'<span class="label '+ mapStatus[classe.status].label +'">'+ mapStatus[classe.status].status +'</span>'+
									'</div>'+
								'</fieldset>'+
							'</div>'+
						'</li>'
					);
				});
				modal.modal();
			}
			else {
				alert('Não existem turmas no ano anterior');
			}
		});
  });

	$("#formReceiveClass").submit(function(e) {
		e.preventDefault();
		var fields = [];

		$(e.target).find('fieldset').each(function(i, field) {
			var classe = $(field).serializeArray();

			if(classe.length == 2) {
				fields.push({ 'classe_id' : classe[0].value, 'with_offers': classe[1].value });
			}

		});

		// var obj = { 'classes_id': []};
		// fields.forEach(function(item, i) {
		// 	if(item.name == 'classe_id') {
		// 		obj.classes_id.push(item.value);
		// 	}
		// });

		if(!fields.length) {
			dialog.info('Alerta', 'Nenhuma turma selecionada');
			return;
		}

		dialog.confirm('Confirmação', 'Deseja confirmar essa operação?', function() {
			dialog.waiting('Processando. Aguarde.');
			dialog.close('confirm');
			$.post('classes/copy-to-year', {'classes': fields}, function(data) {
				if(data.status) {
					dialog.info('Sucesso', 'A operação foi concluída com sucesso.', function() {
						location.reload();
					});
				}
				dialog.close('waiting');
			}).fail(function() {
				dialog.info('Erro', 'Não foi possível finalizar o processamento. Se o erro persistir, contate o suporte');
			});

		});
	});

});
