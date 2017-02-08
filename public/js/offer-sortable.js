$(function(){
  
  $("#listOffers").sortable({
    placeholder: 'sortHolder',
    start: function(event, ui) {
        ui.item.toggleClass("sortFocus");
    },
    stop: function(event, ui) {
        ui.item.toggleClass("sortFocus");
    },
    update: function(event, ui) {
      order = $(this).sortable( "toArray", { attribute: "data-id" } );
      //console.log(order);
      $.post("/lectures/sort", {order: order}, function(data){
        //console.log(data);
      });
    },
    opacity: 0.9,
    scrollSpeed: 10,
    cursor: "move",
    forcePlaceholderSize: true
  });

});