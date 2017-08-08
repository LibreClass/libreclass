var _this = null;

$(function() {

  $('.infolesson').attr({
    'data-toggle': 'tooltip',
    'data-placement': 'top',
    'rel': 'tooltip',
    'title': 'Visualizar aula'
  });

  /**
   * Habilita o funcionamento dos tooltips
   */
  $(function() {
    $('[rel="tooltip"]').tooltip();
  });

  events();

});


/**
 * carrega todos eventos da página
 *
 * @returns {undefined}
 */
function events(){

  $(".infolesson").unbind( "click" ).click(function() {

    var lesson = $(this).closest(".data").attr("key");
    var lessonseq = $(this).prev(".seq").find('p').text();
   // alert(lessonseq);
     $.getJSON("/lessons/info", {
        "lesson": lesson
      })
      .done(function(data){
        var lesson = $(this).closest(".data").attr("key");
    //console.log(data);
        if(data) {
          $('#modalInfoLesson .lesson-date').html(data.date);
          $('#modalInfoLesson .lesson-title').html(data.title);
          $('#modalInfoLesson .lesson-estimatedTime').html(data.estimatedTime +" min");
          $('#modalInfoLesson .lesson-description').html(data.description);
          $('#modalInfoLesson .lesson-goals').html(data.goals);
          $('#modalInfoLesson .lesson-content').html(data.content);
          $('#modalInfoLesson .lesson-methodology').html(data.methodology);
          $('#modalInfoLesson .lesson-valuation').html(data.valuation);
          $('#modalInfoLesson .lesson-bibliography').html(data.bibliography);
          $('#modalInfoLesson .lesson-notes').html(data.notes);
          $("#modalInfoLesson").modal();
        }
        else {
          $('#modalAlert .modal-body').html("<h4>Não foi possível carregar sua aula. Se esse erro persistir contate o suporte.</h4>");
          $("#modalAlert").modal();
        }
      })
      .fail(function() {
        $('#modalAlert .modal-body').html("<h4>Não foi possível carregar sua aula. Se esse erro persistir contate o suporte.</h4>");
        $("#modalAlert").modal();
      });


       // $("#form-classe input[name='class']").val(data.class);
       // $("#form-classe input[name='period']").val(data.period);

  });

  $(".lesson-copy, .lesson-copy-with").unbind( "click" ).click(function(){
    $("#lesson-process").show();
    var type = $(this).hasClass("lesson-copy") ? 1 : 2;
    var lesson = $(this).closest(".data").attr("key");
    $.post("/lessons/copy", {
      "lesson" : lesson,
      "type" : type
    })
    .done(function(data){
      var lesson = $("#hidden-lesson").clone();
      $(lesson).prependTo("#list-lessons");
      $(lesson).attr("id", "");
      $(lesson).attr("key", data.id);
      $(lesson).find(".lesson-sort").text("Aula "+($("#list-lessons > li").length-1));
      $(lesson).find(".lesson-edit").attr("href", "/lessons?l="+data.id);
      $(lesson).find(".lesson-date").append(" "+data.date);
      $(lesson).find(".lesson-title").text(data.title);
      $(lesson).find(".lesson-title").attr("href", "/lessons?l="+data.id);
      $(lesson).find(".lesson-description").text(data.description);

      $("#lesson-process").hide();
      $(lesson).show("slow");

      events();
    })
    .fail(function() {
      $("#modalAlert .modal-body").html("<p>Não foi possível copiar a aula.</p>");
      $("#modalAlert").modal();
    });
  });

  $(".lesson-copy-for").unbind( "click" ).click(function(){
    $("#formCopyLessonFor .form-submit").fadeIn();
    $("#formCopyLessonFor .form-process").fadeOut();

    var lesson = $(this).closest(".data").attr("key");
    $("#formCopyLessonFor input[name='lesson']").val(lesson);
		$("#formCopyLessonFor select[name='offer']").empty();

    $.post("/lessons/list-offers", {})
     .done(function(data){
      $.each(data, function (i, item) {
        $("#formCopyLessonFor select[name='offer']").append($('<option>', {
            value: item.id,
            text : item.courseName + ' / ' + item.periodName + ' / ' + item.class + " / " + item.name
        }));
      });

      $("#modalCopyLessonFor").modal();
    });
  });

  $("#formCopyLessonFor").submit(function() {
    $("#formCopyLessonFor .form-submit").fadeOut();
    $("#formCopyLessonFor .form-process").fadeIn();
    $.post("/lessons/copy", {
      lesson: $("#formCopyLessonFor input[name='lesson']").val(),
      offer : $("#formCopyLessonFor select[name='offer']").val(),
      type : 3
    })
      .done(function() {
        $("#modalCopyLessonFor").modal("hide");
        $("#modalAlert .modal-title").html("<span class='text-md'>Aviso</span>");
        $("#modalAlert .modal-body").html("<p>Aula duplicada com sucesso. </p>");
        $("#modalAlert").modal();
      })
      .fail(function() {
        $("#modalAlert .modal-title").html("<span class='text-md'>Erro</span>");
        $("#modalAlert .modal-body").html("<p>Não foi possível duplicar a aula.</p>");
        $("#modalAlert").modal();
      });
    return false;
  });

  $("#insert-plan").unbind( "click" ).click(function() {
    $("#plan-lesson").toggle("fast");
  });

  $("#insert-notes").unbind( "click" ).click(function() {
    $("#notes-lesson").toggle("fast");
  });

  $(".remove-student").unbind( "click" ).click(function() {
    $("#rmstudent input[name='student']").val($(this).closest(".list-student").attr("id"));
    $("#rmstudent").submit();
  });

  $(".status-student-offer").unbind( "click" ).click(function() {
    $("#statusStudentOffer input[name='student']").val($(this).closest(".list-student").attr("id"));
    $("#statusStudentOffer input[name='status']").val($(this).attr("data"));
    $("#statusStudentOffer").submit();
  });

  $(".change-frequency").unbind( "click" ).click(function(){

    if ( _this ) {
      alert("O LibreClass ainda está enviando a última solicitação. Por favor, aguarde.");
      return false; /* evita multiplas solicitações */
    }

    _this = this;
    $(this).addClass("btn-default").removeClass("btn-danger btn-primary");
    $.post("/lessons/frequency", {
      "idAttend": $(this).closest("tr").attr("id"),
      "idLesson": $(this).closest("tbody").attr("id"),
      "value":    $(this).text()
    }, function (data) {

      if ( data.status ) {
        $(_this).text(data.value);
        $(_this).closest("td").next("td").html(data.frequency);
        if ( data.value == "P")
          $(_this).addClass("btn-success").removeClass("btn-default");
        else
          $(_this).addClass("btn-danger").removeClass("btn-default");
      }

      _this = null;
    }) .fail(function() {
      alert( "error" );
      if ( $(_this).text() == "P" )
        $(_this).addClass("btn-success").removeClass("btn-default");
      else
        $(_this).addClass("btn-danger").removeClass("btn-default");

      _this = null;
    });

    return false;
  });

	$('#new-lesson-group').unbind('click').click(function(e) {
		e.preventDefault();
		var modalTarget = $('#modalLessonGroupOffers');
		var listOffersGrouped = modalTarget.find('.groupOffersList').empty();
		var id = $(e.currentTarget).attr('offer-id');
		$.post('/offers/get-grouped', { group_id: id }, function(data) {
			if(!data.status) {
				alert('Não foi possível carregar as ofertas do grupo de ofertas. Se o erro persistir contate o suporte.');
			}
			else {
				data.offers.forEach(function(item) {
					listOffersGrouped.append(
						'<li class="list-group-item"><label><input type="checkbox" name="slaves" value="'+ item.id +'" checked/> '+ item._discipline.name +'</label>'
					);
				});
			}
		});
		modalTarget.modal();
	});

	$("#createLessonGroup").unbind('submit').submit(function(e) {
		e.preventDefault();
		var form = $(e.currentTarget);
		var arr = form.serializeArray();

		obj = {};
		arr.forEach(function(item) {
			if(obj[item.name]) {
				obj[item.name] = $.makeArray(obj[item.name]);
				obj[item.name].push(item.value);
			} else {
				obj[item.name] = item.value;
			}
		});

		obj.slaves = $.makeArray(obj.slaves);

		$.post('/lessons/new', obj, function(data) {
			$('#modalLessonGroupOffers');
			alert('Aula criada com sucesso.');
			location.href = '/lessons?l='+ data.lesson.id;
		}).fail(function() {
			alert('Erro ao criar aula. Se o erro persistir contate o suporte');
		});

	});

}
