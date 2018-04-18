$(function() {
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
 
