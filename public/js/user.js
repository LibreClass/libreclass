function link() {
  $(".result li").click(function(){
    var friend = $(this).attr("data");
    var t = $(this).closest("ul").attr("t");
    
    $.confirm("Tem certeza que deseja adicionar?", function() {
      location.href = "/user/link/"+t+"/"+friend;
    });
  });
}
  
