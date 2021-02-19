$(function(){
  $(".units li").click(function(){
    $(".units li").removeClass("active");
    $(this).addClass("active");
    $("#list-units li").not(".model").remove();
    $.post("/attends/resume-unit/"+$(this).attr("data"), function(data){
      console.log(data);
      for ( i = 0 ; i < data.length ; i++ )
      {
        var client = $("#list-units .model").eq(0).clone();

        $(client).removeClass("model").attr("data", data[i].id).find(".notification-date").append(data[i].date);
        $(client).find(".notification-info").text(data[i].value);
        $(client).find(".notification-title").text(data[i].title);

        if ( data[i].type == "E" )
        {
          var average = parseFloat($("#average").attr("average"));
          $(client).addClass("notification-exam").find(".notification-info").addClass( Number(data[i].value) >= average ? "label-success" : "label-danger");
        }
        else
        {
          $(client).addClass("notification-lesson").find(".notification-info").addClass( data[i].value == "P" ? "label-success" : "label-danger");
        }

        $(client).appendTo("#list-units");
      }
    });
  });

  $(".units li").eq(0).click();
});