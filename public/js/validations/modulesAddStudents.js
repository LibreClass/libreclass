$(function () {

  $('#new-student').validate({
    rules: { 
      "enrollment": { 
        required: true 
      },
      "name": {
        required: true
      },
      "email": {
        required: true,
        email: true
      }
    },
    messages: {
      "enrollment": {
        required: "Este campo deve ser preenchido"
      },
      "name": {
        required: "Este campo deve ser preenchido"
      },
      "email": {
        required: "Este campo deve ser preenchido",
        email: "Por favor, insira um email válido"
      }
    }
  });

});