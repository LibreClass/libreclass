$(function () {

  $('#new-student').validate({
    rules: { 
      "enrollment": { 
        required: true 
      },
      "name": {
        required: true
      },
    },
    messages: {
      "enrollment": {
        required: "Este campo deve ser preenchido"
      },
      "name": {
        required: "Este campo deve ser preenchido"
      },
    },
  });

});