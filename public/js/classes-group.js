$(function() {

  // $('div.helper button.btn').on('click', function(e) {
  //   alert($(e.currentTarget).closest('div[data-classe]').attr('data-classe'));
  // });

  $('[type="checkbox"]').on('click', function() {
    if($(this).prop('checked')) {
      $(this).closest('label').addClass('text-success').css('font-weight', 'bold');
    } else {
      $(this).closest('label').removeClass('text-success').css('font-weight', 'normal');
    }
  });

  $('button.step-1').click(function(e) {
    $('button.step-1').closest('.panel').hide();
    $('button.step-2').closest('.panel').fadeIn('fast');
  });

  $('button.step-2').click(function(e){
    e.preventDefault();
    var offers = [];
    $('input:checkbox[name="offers"]:checked').each(function(){
      offers.push($(this).val());
    });
    var classe = $('#block').attr('data-classe');
    var name = $(e.currentTarget).closest('form').find('input[name="name"]').val();

    $.post('/classes/group/create', {
        'offers': offers,
        'classe': classe,
        'name': name
      }, function(data) {
        if (data.status == 1) {
          // Mostra modal de disciplina agrupada com sucesso...
          window.location = '/classes';
        }
      }
    );
  });

});