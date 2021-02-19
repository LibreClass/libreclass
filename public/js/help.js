$(function() {

  /**
   * Carrega informações dos tooltips
   */
  $('ul.help-list ul li span').attr({
    'data-toggle': 'tooltip',
    'data-placement': 'right',
    'rel': 'tooltip',
    'title': 'Clique aqui para visualizar o módulo de ajuda desta ferramenta'
  });

 /**
  * Carrega o modal com help ao clicar no link
  */
  $('ul.help-list ul li span').click(function() {
    $('#modalHelpLabel').html($(this).html());

    className = $(this).attr('class');
    $('#modalHelpBody').load('/help/'+className.slice(5));

    $('#modalHelp').modal();
  });
});
