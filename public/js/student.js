$(function (){
  $("#pagination a").click(function(){
    $("#find-student input[name='current']").val($(this).attr("href"));
    $("#find-student").submit();
    return false;
  });

	// Cria e edita alunos.
  $(".new-student", '#view-student').click(function(e) {
		var form = $('#new-student');
		if($(e.currentTarget).is('[edit]')) {
			var student_id = $(e.currentTarget).closest('tr.student-item').attr('data-relationship-id');
			$.post('/user/get-student', { 'student_id': student_id }, function(data) {
				form.trigger('reset');


				var day = '';
				var month = '';
				var year = '';

				if(data.student.birthdate) {
					day = data.student.birthdate.split('-')[2];
					month = data.student.birthdate.split('-')[1];
					year = data.student.birthdate.split('-')[0];
					form.find('[name="date-day"]').val(parseInt(day));
					form.find('[name="date-month"]').val(parseInt(month));
					form.find('[name="date-year"]').val(year);
				}

				form.find('[name="student_id"]').val(data.student.id);
				form.find('[name="enrollment"]').val(data.student.enrollment);
				form.find('[name="name"]').val(data.student.name);
				form.find('[name="gender"]').val(data.student.gender);
				form.find('[name="email"]').val(data.student.email || "");
				form.find('[name="course"]').val(data.student.course);

				$('#block', '#view-student').hide();
				$(".block-search-student", '#view-student').hide();
				$("#block-add, .block-new-student", '#view-student').show();
			});
		}
		else {
			form.trigger('reset');
			$(".block-search-student", '#view-student').hide();
			$(".block-new-student", '#view-student').show();

		}
  });

  $("#search-student").submit(function(){
    $('#result-search-student').load("/user/find-user/"+encodeURI($(this).find("input:text").val()),
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

  $("#btnReport").unbind("click").click(function() {
    $("#modalScholarReport").modal();
  });

  $("#modalScholarReport").on("submit", "#form-scholar-report", function(e) {
    e.preventDefault();
    var student = new URLSearchParams(window.location.search).get('u');
		var data = $(e.currentTarget).serialize();
    window.open($(e.currentTarget).attr('data-url') + '?u=' + student + '&' + data);
  });

  $("#classe").change(function(){
    var class_id = $(this).val();
    if (!class_id) {
      $("#list-offers").html('Nenhuma turma selecionada');
      return;
    }

    $("#list-offers").html('Carregando...');
    $.post("/classes/list-offers",{
      class: class_id,
      student: $("#formLinkStudentClasse input[name='student']").val()
    }, function(data){
      $("#list-offers").html("");
      for (var i = 0 ; i < data.length ; i++)
        $("#list-offers").append($("<input>", {
          type: "checkbox",
          name: "offers[]",
          value: data[i].id,
          checked: data[i].status > 0,
          disabled: data[i].status > 0
        })).append(data[i].name).append("<br>");
    }).fail(function() {
      alert("error");
    });
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

	$('#select-scholar-report-course').change(function(e) {
		var quant_unit = $(e.target).find('option:selected').attr('quant-unit');
		if(quant_unit == 0) {
			quant_unit = 4;
		}
		$("#modalScholarReport").find(".list-units-course").empty();
		for(var i=1; i <= quant_unit; i++) {
			$("#modalScholarReport").find(".list-units-course").append(
				'<div class="col-xs-3">'+
					'<div class="checkbox">'+
						'<label><input type="checkbox" name="unit_value[]" value="'+ i +'" checked>Unidade '+ i +'</label>'+
					'</div>'+
				'</div>'
			);
		}
	});

	$('#select-scholar-report-course').trigger('change');
});
