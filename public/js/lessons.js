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

  $(".infolesson").click(function() {

    var lesson = $(this).closest(".data").attr("key");
    var lessonseq = $(this).prev(".seq").find('p').text();
    // alert(lessonseq);
    $.getJSON("/lessons/info", {
        "lesson": lesson
      })
      .done(function(data) {
        var lesson = $(this).closest(".data").attr("key");
        //console.log(data);
        if (data) {
          $('#modalInfoLesson .lesson-date').html(data.date);
          $('#modalInfoLesson .lesson-title').html(data.title);
          $('#modalInfoLesson .lesson-estimatedTime').html(data.estimatedTime + " min");
          $('#modalInfoLesson .lesson-description').html(data.description);
          $('#modalInfoLesson .lesson-goals').html(data.goals);
          $('#modalInfoLesson .lesson-content').html(data.content);
          $('#modalInfoLesson .lesson-methodology').html(data.methodology);
          $('#modalInfoLesson .lesson-valuation').html(data.valuation);
          $('#modalInfoLesson .lesson-bibliography').html(data.bibliography);
          $('#modalInfoLesson .lesson-notes').html(data.notes);
          $("#modalInfoLesson").modal();
        } else {
          $('#modalInfoLesson .modal-body').html("<h4>Não foi possível carregar sua aula. Se esse erro persistir contate o suporte.</h4>");
          $("#modalInfoLesson").modal();
        }
      })
      .fail(function() {
        $('#modalInfoLesson .modal-body').html("<h4>Não foi possível carregar sua aula. Se esse erro persistir contate o suporte.</h4>");
        $("#modalInfoLesson").modal();
      });


    // $("#form-classe input[name='class']").val(data.class);
    // $("#form-classe input[name='period']").val(data.period);

  });

  $("#insert-plan").click(function() {
    $("#plan-lesson").toggle("fast");
  });

  $("#insert-notes").click(function() {
    $("#notes-lesson").toggle("fast");
  });

  $(".remove-student").click(function() {
    $("#rmstudent input[name='student']").val($(this).closest(".list-student").attr("id"));
    $("#rmstudent").submit();
  });

  $(".change-frequency").click(function() {

    if (_this) {
      alert("calma ai");
      return false; /* evita multiplas solicitações */
    }

    _this = this;
    $(this).addClass("btn-default").removeClass("btn-danger btn-primary");
    $.post("/lessons/frequency", {
      "idAttend": $(this).closest("tr").attr("id"),
      "idLesson": $(this).closest("tbody").attr("id"),
      "value": $(this).text()
    }, function(data) {

      if (data.status) {
        $(_this).text(data.value);
        if (data.value == "P")
          $(_this).addClass("btn-success").removeClass("btn-default");
        else
          $(_this).addClass("btn-danger").removeClass("btn-default");
      }

      _this = null;
    }).fail(function() {
      alert("error");
      if ($(_this).text() == "P")
        $(_this).addClass("btn-success").removeClass("btn-default");
      else
        $(_this).addClass("btn-danger").removeClass("btn-default");

      _this = null;
    });

    return false;
  });

});
