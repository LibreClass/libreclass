$(function () {

  $('#new-teacher').validate({
    rules: {
      "name": {
        required: true
      },
      "email": {
        required: true,
        email: true
      }
    },
    messages: {
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