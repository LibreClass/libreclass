$(function () {
	$('[data-toggle="tooltip"]').tooltip();
	$("input[mask='date']").mask("99/99/9999");
	$("input[mask='cpf']").mask("99999999999");
	$("input[mask='nis']").mask("99999999999");
	$("input[mask='cep']").mask("99999999");
	
	$(".toogle-form").click(function() {
		var form = $(this).next("form");
		var icon = $(this).find(".icon-toogle");
		
		if(!$(form).is(":visible")) {
			$(form).show();
			$(icon).removeClass("fa-chevron-down").addClass("fa-chevron-up");
		}
		else {
			$(form).hide();
			$(icon).removeClass("fa-chevron-up").addClass("fa-chevron-down");
		}
	});
});