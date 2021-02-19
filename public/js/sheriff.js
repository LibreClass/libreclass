
$.fn.sheriff = function(url){
	$(this).append($("<i>", {
		class: "json-sheriff",
		hidden:"hidden"
	})).addClass("main-sheriff");

	$(this).find(".json-sheriff").load( url, function(){
		json = JSON.parse($(this).html());
		var list = $(this).closest(".main-sheriff").find(".list-sheriff");
		for( i = 0 ; i < json.length ; i++ )
		{
			var model = $(list).find(".model").eq(0).clone().removeClass("model");
			$(model).attr("data", json[i].id).find(".label-image").html($("<img>", {
				class: "img-responsive img-circle",
				src: json[i].photo == "" ? "/images/user-photo-default.jpg" : json[i].photo
			})).next(".label-name").html(json[i].name);//.next(".label-comment").html(json[i].comment);
			// $(client).find("img").attr("src", "/img/partners/"+data[i].__id+".png").attr("alt", data[i].name);

			$(list).append(model);
		}
		$(list).find(".model").eq(0).remove();

		$(list).find("li").click(function(){
			$(this).removeClass("hover").addClass("selected").hide();
			var list = $(this).closest(".main-sheriff").find(".insert-sheriff");
			var model = $(list).find(".model").eq(0).clone().removeClass("model");
			$(model).find(".label-name").text($(this).find(".label-name").text());
			$(model).find(".label-image").html($(this).find(".label-image").html());
			$(model).append($("<input>", {
				value: $(this).attr("data"),
				name: "teachers[]",
				type: "hidden"
			}));
			$(model).find(".label-remove").click(function(){
				$(this).closest(".main-sheriff")
					.find(".list-sheriff")
					.find("li[data='"+$(this).closest("li").find("input").val()+"']").removeClass("selected");
				$(this).closest(".main-sheriff").find(".input-sheriff").focus();
				$(this).closest("li").remove();
			});
			$(list).append(model);

			$(this).closest(".main-sheriff").find(".input-sheriff").val("").focus();
		}).mouseenter(function(){
			$(this).closest("ul").find("li").removeClass("hover");
			$(this).addClass("hover");
		});
	});

	$(this).find(".input-sheriff").on("focusin keyup", function(e){
		if (e.keyCode == 40 || e.keyCode == 38 || e.keyCode == 13) return false;
		$.sheriffSearch = $(this).val().toLowerCase();
		$.sheriffList = $(this).next(".list-sheriff").find("li");

		if($.sheriffSearch.length)
			$(this).closest(".dropdown").addClass("open");
		else
		{
			$(this).closest(".dropdown").removeClass("open");
			$(this).closest(".main-sheriff").find(".list-sheriff li.hover").removeClass("hover");
			return;
		}

		clearTimeout($.sheriffTimer);
		$.sheriffTimer = setTimeout(function(){
			var first = true;
			for(i = 0 ; i < $($.sheriffList).length ; i++)
			{
				var name = $($.sheriffList).eq(i).find(".label-name").text().toLowerCase();
				if (name.indexOf($.sheriffSearch) >= 0 && !$($.sheriffList).eq(i).hasClass("selected"))
				{
					$($.sheriffList).eq(i).show();
					if(first){
						$($.sheriffList).eq(i).addClass("hover");
						first = false;
					}
					else
						$($.sheriffList).eq(i).removeClass("hover");
				}
				else
					$($.sheriffList).eq(i).hide();
			}
		}, 500);
	}).keypress(function(e){
		switch(e.keyCode)
		{
			case 13:
				zz
				return false;
			case 38:
				var li = $(this).closest(".main-sheriff").find(".list-sheriff li.hover");
				if ($(li).prev().length && !$(li).prev().hasClass("model"))
					$(li).removeClass("hover").prev().addClass("hover");
				return false;
			case 40:
				var li = $(this).closest(".main-sheriff").find(".list-sheriff li.hover");
				if ($(li).next().length)
					$(li).removeClass("hover").next().addClass("hover");
				return false;
		}
	});
}
