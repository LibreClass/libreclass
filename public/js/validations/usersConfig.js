$(function () {

//  e.preventDefault();
  
  $('#nameUpdate').validate({
    rules: { 
      "name": { 
        required: true 
      } 
    },
    messages: {
      "name": {
        required: "Este campo deve ser preenchido"
      }
    }
  });

  $('#emailUpdate').validate({
    rules: { 
      "email": { 
        required: true,
        email: true
      } 
    },
    messages: {
      "email": {
        required: "Este campo deve ser preenchido",
        email: "Este campo deve possuir um email válido"
      }     
    }
  });

  $('#passwordUpdate').validate({
    rules: { 
      "password": { 
        required: true
      },
      "newpassword": {
        required: true,
        minlength: 5 
//        equalTo: "#password"
      }
    },
    messages: {
      "password": {
        required: "Este campo deve ser preenchido"
//        minlength: "Senha muito curta"
      },
      "newpassword": {
        required: "Este campo deve ser preenchido",
        minlength: "A senha deve ter no mínimo 5 caracteres"
      }
    }
  });

  $('#courseUpdate').validate({
    rules: { 
      "course": { 
        required: true 
      } 
    },
    messages: { 
      "course": {
        required: "Este campo deve ser preenchido"
      }     
    }
  });
 
  $('#institutionUpdate').validate({
    rules: { 
      "institution": { 
        required: true 
      } 
    },
    messages: {
      "institution": {
        required: "Este campo deve ser preenchido"
      }     
    }
  });

});