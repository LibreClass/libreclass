var _this = null;

$(function() {

  $("#exam-form").submit(function() {
    return false;
  });

  $(".exam-value").keyup(function(event) {
    var nTecla = event.which;
    if ((nTecla > 47 && nTecla < 58) || (nTecla >= 96 && nTecla <= 105) || nTecla == 8 || nTecla == 127 || nTecla == 0 || nTecla == 9 || nTecla == 108 || nTecla == 188 || nTecla == 190 || nTecla == 13) {} else {
      this.value = this.value.substr(0, this.value.length - 1);
      return false;
    }
  });

  $(".exam-value").keyup(function(event) {
    if (event.keyCode == 13) {
      $(".exam-value").attr("disabled", true);
      _this = this;
      $(_this).next(".feedback-response").html("<i class='fa fa-spinner fa-spin'></i>");

      $.post("/avaliable/offer", {
        value: $(this).val(),
        student: $(this).closest("tr").attr("id"),
        offer: $(this).closest("tbody").attr("id")
      }, function(data) {
        $(_this).closest("div").removeClass("has-error has-warning").addClass("has-success");
        $(_this).closest("div").next(".feedback-response").html("<span class='label label-success'><i class='fa fa-check'></i><b> Salvo</b></span>");
        $(".exam-value").attr("disabled", false);
        $(_this).closest("tr").next("tr").find("input").focus();
        if (data == "error") {
          $(_this).closest("div").removeClass("has-success has-warning").addClass("has-error");
          $(_this).closest("div").next(".feedback-response").html("<span class='label label-danger'><i class='fa fa-remove'></i><b> Inválido</b></span>");
        } else {
          $(_this).val(data);
          $(_this).attr("data", data);
        }
      });
      //       alert($(this).val());

    } else {
      $(this).closest("div").next(".feedback-response").html("<span class='label label-default'>Enter para salvar</span>");
    }
  });

  $(".exam-value").change(function() {
    $(this).closest("div").removeClass("has-error has-success").addClass("has-warning");
    $(this).closest("div").nextAll(".feedback-response").html("<span class='label label-warning'><i class='fa fa-warning'></i><b> Não Salvo</b></span>");
  });

  $(".back-avaliable").click(function() {
    $(this).prevAll("input").val($(this).prevAll("input").attr("data")).focus();
  });

  $(".submit-avaliable").click(function() {
    $(this).prevAll("input").keyup();
  });
});
