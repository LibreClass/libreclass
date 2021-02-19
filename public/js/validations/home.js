$(function () {

  $('#loginForm').validate({

    // Regras de validação
    rules: {

      "name": {
        required: true,
        minlength: 3
      },
      "email": {
        required: true
      },
      "password": {
        required: true
      }
      
    },

    // Mensagens de erro
    messages: {

      "name": {
        required: "Este campo deve ser preenchido",
        minlength: "Este campo deve possuir tamanho mínimo de {0} caracteres"
      },
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