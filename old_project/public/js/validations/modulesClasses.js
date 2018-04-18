$(function () {

  $('#addClasses').validate({
    rules: { 
      "class": { 
        required: true 
      }
    },
    messages: {
      "class": {
        required: "Este campo deve ser preenchido"
      }
    }
  });

});