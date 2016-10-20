$(function(){
  $(".view-syllabus").click(function(){
    //$(this).closest("div").next("div").find(".syllabus").toggleClass("visible-none");
    discipline = $(this).attr("key");
    
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
    $(this).next(".modalTeacherOffer").modal();
  });


  $(".toggle-btn").bootstrapToggle({
    on: 'Ativa',
    off: 'Desativada'
  });

  $('.toggle-event').change(function(){
    $.post("/classes/offers/status", {
      status:$(this).prop('checked'),
      unit:$(this).attr('unit')
    }, function(data){
      //alert(data);
    });
  });

});

