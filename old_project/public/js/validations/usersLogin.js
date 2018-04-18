$(function () {

  $('#loginForm').validate({

    // Regras de validação
    rules: {

      "email": {
        required: true,
        email: true
      },
      "password": {
        required: true
      }
      
    },

    // Mensagens de erro
    messages: {

      "email": {
        required: "Este campo deve ser preenchido",
        email: "Este campo deve ser um email"
      },
      "password": {
        required: "Este campo deve ser preenchido"
      }
      
    }

  });
});