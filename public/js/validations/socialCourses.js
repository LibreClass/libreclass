$(function () {

  $('#form-course').validate({
    rules: { 
      "name": { 
        required: true 
      },
      "absentPercent": {
        required: true,
        number: true,
        min: 0,
        max: 100
      },
      "average": {
        required: true,
        number:true,
        range: [0.01, 9.99]
      },
      "averageFinal": {
        required: true,
        number:true,
        range: [0.01, 9.99]
      }
    },
    messages: {
      "name": {
        required: "Este campo deve ser preenchido"
      },
      "absentPercent": {
        required: "Este campo deve ser preenchido",
        number: "Apenas números inteiros entre 0 e 100",
        max: "O valor não pode ser maior do que 100",
        min: "O valor não pode ser menor do que 0"
      },
      "average": {
        required: "Este campo deve ser preenchido",
        number: "Apenas números entre 0 e 10",
        range: "O número deve ser maior que 0 e menor que 10"
      },
      "averageFinal": {
        required: "Este campo deve ser preenchido",
        number: "Apenas números entre 0 e 10",
        range: "O número deve ser maior que 0 e menor que 10"
      }
    }
  });

});