var dialog = null, d = null;
(function() {
	var dialog_tmp = function() {

		/* Diálogo de waiting */
		this.waiting = function(m) {
			var html = '<div class="dialog z-depth-1 dialog-waiting" hidden>'+
					'<div class="dialog-body text-center text-sm">' + m + '</div>'+
				'</div>';
			var modal = this.open(html);
		};

		/* Diálogo de confirm */
		this.confirm = function(title, message, confirm, cancel) {
			var data = { confirm: {}, cancel: {} };
			data.title = title;
			data.class = '';
			data.message = message;
			data.confirm.event = confirm || "";
			data.cancel.event = cancel || "";
			data.cancel.label = "CANCELAR";
			data.confirm.label = "OK";
			if(typeof title == "object") {
				data = $.extend(true, data, title);
			}

			if(data.full) data.class += ' dialog-full';

			var html = '<div class="dialog dialog-confirm z-depth-1 '+data.class+'" hidden><div class="dialog-content">';
			if(data.title !== "") {
				html += '<div class="dialog-header">' + data.title + '</div>';
			}
			html += '<div class="dialog-body">' + data.message + '</div>'+
							'<div class="dialog-footer">'+
								'<button class="btn-flat dialog-cancel waves-effect">'+ data.cancel.label +'</button>'+
								'<button class="btn-flat dialog-ok waves-effect">'+ data.confirm.label +'</button>'+
							'</div>'+
						'</div>'+
					'</div>';
			var modal = this.open(html);

			$(".dialog-cancel", modal).on("click", function() {
				if(typeof data.cancel.event == 'function') {
					if ( data.cancel.event() === false ) return false;
				}
				$.dialog.close(modal);
			});

			$(".dialog-ok", modal).on("click", function() {
				if(typeof data.confirm.event == 'function') {
					if ( data.confirm.event() === false ) return false;
				}
				if(!$(".dialog-waiting").length) {
					$.dialog.close(modal);
				}
			});
		};

		this.prompt = function(title, m, label, selector, func1, func2) {
			func1 = func1 || "";
			func2 = func2 || "";
			var html = '<div class="dialog z-depth-1" hidden>'+
					'<div class="dialog-header">' + title + '</div>'+
					'<div class="dialog-body">' + m + '<br>'+
						'<label>' + label + '</label>'+
					'<div class="form-group">'+
						'<input	type="text" class="' + selector + '" name="' + selector + '" autofocus>'+
					'</div>'+
					'</div>'+
					'<div class="dialog-footer">'+
						'<button class="btn-flat dialog-cancel waves-effect">CANCELAR</button>'+
						'<button class="btn-flat dialog-ok waves-effect">OK</button>'+
					'</div>'+
				'</div>';
			var modal = this.open(html);

			$(".dialog-cancel").on("click", function() {
				func2();
				$.dialog.close(modal);
			});

			$(".dialog-ok").on("click", function() {
				func1();
				$.dialog.close(modal);
			});

		};

		this.modal = function(title, m, func) {
			func = func || "";
			var html = '<div class="dialog z-depth-1" hidden>'+
					'<div class="dialog-header">' + title + '</div>'+
						'<div class="dialog-body">' + m + '</div>'+
						'<div class="dialog-footer">'+
						'<button class="btn-flat btn-block dialog-cancel waves-effect">FECHAR</button>'+
					'</div>'+
				'</div>';
			var modal = this.open(html);

			$(".dialog-cancel").on("click", function() {
				$.dialog.close(modal);
			});
			func();

		};

		/* Diálogo de info */
		this.info = function(title, m, func) {
			func = func || "";
			var html = '<div class="dialog dialog-info z-depth-1">';
			if(title !== "") {
				html += '<div class="dialog-header">' + title + '</div>';
			}
			html += '<div class="dialog-body">' + m + '</div>'+
								'<div class="dialog-footer">'+
								'<button class="btn-flat btn-block dialog-cancel waves-effect">FECHAR</button>'+
							'</div>'+
						'</div>';
			var modal = this.open(html);
			$(".dialog-cancel").on("click", function() {
				if(typeof func == 'function') func();
				$.dialog.close(modal);
			});
		};

		/* Diálogo de help */
		this.help = function(title, m, obj) {
			obj = obj || {};
			var html = '<div class="dialog dialog-help z-depth-1 '+ (obj.class || '') +'">'+
								'<div class="dialog-content">'+
									'<div class="dialog-header">' + title + '</div>'+
									'<div class="dialog-body dialog-body-help">' + m + '</div>'+
									'<div class="dialog-footer">'+
										'<button class="btn-block btn-flat dialog-cancel waves-effect">FECHAR</button>'+
									'</div>'+
								'</div>'+
							'</div>';
			var modal = this.open(html);
			$(".dialog-cancel").on("click", function() {
				if(typeof obj.func == 'function') obj.func();
				$.dialog.close(modal);
			});
		};

		this.menu = function(title, obj, event) {

			var html = '<div class="dialog z-depth-1" hidden>'+
										'<div class="dialog-header">'+
											'<div class="dialog-header-title">' + title + '</div>'+
											'<div class="dialog-close"></div>'+
										'</div>'+
										'<div class="dialog-body"><ul class="dialog-menu"></ul></div>'+
									'</div>';
			var modal = this.open(html);

			var options = "";
			for(var i in obj) {
				options += "<li class='list-item' data-value='"+obj[i].value+"'>"+ obj[i].label +"</li>";
				if (obj[i].event) $('.dialog-menu').on('click', "li:eq("+i+")", function(e) {
					$.dialog.close(modal);
					obj[i].event(e);
				});
			}
			$(".dialog-menu").append(options);

			$('.dialog-close').on('click', function() {
				$.dialog.close(modal);
			});

			if (event) {
				$('.dialog-menu li').on('click', function(e) {
					$.dialog.close(modal);
					event(e);
				});
			}
			this.position();
		};
		/* Coloca o diálogo no final do body e show() */
		this.open = function(html) {
			html = $(html);
			$("body").append(html);
			this.position();

			// $(document).keyup(function(e) {
			// 	if (e.keyCode == 27) {
			// 		$(".dialog-overlay").remove();
			// 		$(".dialog").remove();
			// 	}
			// });
			if ( !$(".dialog-overlay").length ) $("body").addClass("noscroll").append("<div class='dialog-overlay'></div>");
			return html;
		};

		this.position = function() {
			this.height = $(window).height();
			this.width = $(window).width();

			if (this.height <= 480) {
				$(".dialog").width(this.width - 40);
			}
			$(".dialog").fadeIn(100);
			//console.log([$(".dialog").height(), $(".dialog").width()]);
			var dialogBody = $(".dialog .dialog-body").outerHeight();
			var dialogHeader = $(".dialog .dialog-header").innerHeight();
			var dialogFooter = $(".dialog .dialog-footer").innerHeight();
			var boxH = $(".dialog").height();
			var boxW = $(".dialog").width();

			if((boxH + 60) > $(window).height() ) {
				$(".dialog").css({
					height: $(window).height() - 60
				});
				boxH = $(".dialog").height();
				// console.log([dialogHeader, dialogBody, dialogFooter, $(".dialog").height()]);
				// console.log($(window).height());
				$(".dialog-body").css({ height: $(".dialog").height() - dialogHeader - dialogFooter });
				$(".dialog-body").css({ overflow: 'auto' });
				// console.log($(".dialog-body").height());
			}

			$(".dialog").css({
				top: this.height / 2 - boxH / 2,
				left: this.width / 2 - boxW / 2
			}).show(0);
		};

		this.close = function(type) {
			if ( typeof type == 'object' ) {
				$(type).fadeOut(100, function() {
					$(type).remove();

					if ( !$('.dialog').length ) {
						$("body").removeClass("noscroll");

						$(".dialog-overlay").fadeOut(100, function() {
							$(this).remove();
						});
					}

				});
			}
			else if ( type && $('.dialog').not('.dialog-'+type).length ) {
				$('.dialog-'+type).remove();
			}
			else {
				$("body").removeClass("noscroll");
				$(".dialog-overlay").fadeOut(100, function() {
					$(this).remove();
					$(".dialog").remove();
				});
			}
		};
	};

	d = dialog = $.dialog = new dialog_tmp();
})(jQuery);
