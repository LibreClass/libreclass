$(function() {

  $('div.helper button.btn').on('click', function() {
    alert(window.location.pathname);
  });

});


function argument(i) {
  var hash = location.hash.replace('#', '').split('/');
  return (typeof hash[i] == 'undefined' ? null : hash[i]);
}