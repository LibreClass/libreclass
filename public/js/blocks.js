//alert = function (e) {
//
//}

function focusFirstField(){
  var f_form = window.document.forms[0];
  if(f_form){
    var n_fields = f_form.length;
    for(var i = 0; i < n_fields; i++){
      var ro = f_form[i].readOnly;
      if(ro == true){
        var sf = i+1;
      }
    }

    if(sf){
      f_form[sf].focus();
    }else{
      f_form[0].focus();
    }
    return true;
  }else{
    var tags = document.getElementsByTagName('input');
    if(tags.length > 0){
      tags[0].focus();
    }else{
      return false;
    }
  }
}

$.confirm = function( msg, func ) {

  $("#modalConfirm #confirm-text").text(msg);

  $("#confirm-message button").unbind().click(function(){
    $("#modalConfirm").modal("hide");
  });
  $("#modalConfirm #confirm-yes").click(func);

  $("#modalConfirm").modal();
};

$.alert = function( title, message, error){
  error = error || false;

  $("#modalAlert .modal-title").html(title);
  $("#modalAlert .modal-body").html(message);
  if (error)
		$("#modalAlert .modal-header").removeClass("modal-header-success").addClass("modal-header-error");
  else
		$("#modalAlert .modal-header").removeClass("modal-header-error").addClass("modal-header-success");

  $("#modalAlert").modal();

}

function changeStatus() {
  var key   = $(this).closest(".data").attr("key");
  //alert(key);

  $("#form-change-status input[name=\"key\"]").val(key);
  $("#form-change-status input[name=\"status\"]").val($(this).attr("value"));

  if ( $(this).attr("href") === undefined ) {
    $("#form-change-status").attr("action", window.location.href + "/change-status");
  }
  else {
    $("#form-change-status").attr("action", $(this).attr("href"));
  }

 	var text = 'reativar';
	if($(this).attr("value") == "B") text = 'bloquear';
	else if($(this).attr("value") == "F") text = 'encerrar';
	else if($(this).attr("value") == "E") text = 'desbloquear';
	// alert();
  $.confirm("Tem certeza que deseja "+text+"?", function() {
    $("#form-change-status").submit();
  });
  return false;
}

function trash() {
  var key   = $(this).closest(".data").attr("key") || $(this).closest(".data").attr("data");
  if ( $(this).attr("href") === undefined ) {
    $("#form-trash input[name=\"input-trash\"]").val(key);
    $("#form-trash").attr("action", window.location.href + "/delete");
  }
  else {
    $("#form-trash input[name=\"input-trash\"]").val(key);
    $("#form-trash").attr("action", $(this).attr("href"));
  }
   // alert($("#form-trash").attr("action"));

    $.confirm("Tem certeza que deseja excluir?", function() {
      $("#form-trash").submit();
    });
    return false;
}


$(function() {
  //alert($("#form-trash").length);
  $(".trash").click(trash);
  $(".change-status").click(changeStatus);

  $("#report").click(function(){
    $("#modalReport").modal();
  });

  $("#new-block").click(function(){
    $("#block").hide();
    $("#block-add").fadeIn();
    // $("form input[name='name']").val("").focus();
  });

   $("#btn-back").click(function(){
       $("#block-add").hide();
        $("#block").fadeIn();
   });

  $("#block-new-student").click(function(){
    $("#block").hide();
    $(".block-new-student, .result").hide();
    $("form#search-student input[name='search']").val("");
    $(".result li").remove();
    $(".block-search-student").show();
    $("#block-add").fadeIn();
  });

// APENAS NÚMEROS..
//    $("input[name='absent_percent']").keyup(function(){
//        var newvalue = $(this).val().replace(/[^0-9]+/g,'');
//        $(this).val(newvalue.substring(0,2));
//    });

//    $("input.grade").keyup(function(){
//        var newvalue = $(this).val().replace(/[^0-9,\.]+/g,'');
//        $(this).val(newvalue);
//    });

//    $("input.grade").blur(function(){
//        //var regex = /^\d{1,2}((\.|\,)\d{0,2})?$/gmi;
//        if (!$(this).val().match(/^\d{1,2}((\.|\,)\d{0,2})?$/g)) {
//            //alert('Formato de nota inválido');
//            $(this).closest(".form-group").addClass("has-error");
//            $(this).focus();
//        }
//    });

    $('#successMessage').modal();
    $('.closeMessage').click(function(){
        $('#messageConfirm').modal('hide');
    });

   $('.dropdown-toggle').dropdown();
});

