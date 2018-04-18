$(function() {

  $('#new-discipline').validate({
    rules: { 
      "period": { 
        required: true 
      },
      "name": {
        required: true
      }
    },
    messages: {
      "period": {
        required: "Este campo deve ser preenchido"
      },
      "name": {
        required: "Este campo deve ser preenchido"
      }
    }
  });

});