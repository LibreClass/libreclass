$(function(){
	$("#new-permission").click(function(){
		$("#modalNewUser input, #modalNewUser select").not("[name='ctrl[]']").val("");
		$("#modalNewUser select").removeAttr("readonly");
	});

	$("#modalNewUser select[name='id']").change(function(){
		$("#modalNewUser input[name='ctrl[]']").removeAttr("checked");
		$.post("/permissions/find",{
			id: $(this).val()
		}, function(data){
			$("#modalNewUser input[name='email']").val(data.email);
			for( i = 0 ; i < data.modules.length ; i++ )
				$("#modalNewUser input[value='"+data.modules[i]+"']").prop({ checked: true });
			console.log(data);
		});
	});

	$(".edit-permission").click(function(){
		$("#modalNewUser select[name='id']").val($(this).attr("data"));
		$("#modalNewUser select[name='id']").attr("readonly", "readonly");
		$("#modalNewUser select[name='id']").change();
		$("#modalNewUser").modal();
	});
});
