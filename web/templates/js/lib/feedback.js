
(function($){
	
	"use strict";
	
	var button = '';
	
	var $button = null;
	
	var sendedSuccess = 'The message was succesfully sended.';
	
	var sendedError = 'There was an error sending the message.';
	
	$.feedback = function(params) {
		
		if (params.constructor === String && methods[params] && methods[params].constructor === Function) {
			
			var args = (Array.prototype.slice.call(arguments));
			args.splice(0, 1);
			
			return methods[params].apply(this, args);
			
		} else {
			
			button = params.button || button;
			
			sendedSuccess = params.sendedSuccess || sendedSuccess;
			sendedError = params.sendedError || sendedError;
			
			$button = $(button);
			
			$button.addClass('fancybox.ajax');
			$button.fancybox({
				height: '300px',
				width: '400px',
				beforeShow: function(){
					
					var $tipo = $('section.feedback .tipo');
					

					var selectedTipo = $(this.element).data('tipo');
					if (!selectedTipo) {
						selectedTipo = 'contido';
					}
	
					$tipo.find('label.selected').removeClass('selected');
					
					$tipo.find('label[data-related="' + selectedTipo + '"]').
							addClass('selected').
							find('input').
								attr('checked', true);
					
					
					$('section.feedback').addClass($tipo.find('label.selected').data('related'));
					
					$tipo.find('label').click(function(){
						$tipo.find('label.selected').removeClass('selected');
						$(this).addClass('selected');
						
						$('section.feedback').attr('class', 'feedback').addClass($(this).data('related'));
					});
					
					$('section.feedback form').submit(function(){
						
						var data = $(this).serialize();
						
						$('section.feedback form').hide();
						$('section.feedback .loading').show();
						
						$.fancybox.update();
						
						$.ajax({
							url: $(this).attr('action'), 
							data: data,
							type: 'POST',
							success: function(data, status, xhr) {
								$('section.feedback').append('<div class="mensaxe-enviado">' + sendedSuccess + '</div>');
							},
							error: function(xhr, status, error) {
								$('section.feedback').append('<div class="mensaxe-erro">' + sendedError + '</div>');
							},
							complete: function(){
								$('section.feedback .loading').hide();
								$.fancybox.update();
							}
						});
						
						return false;
					});
				}
			});
			
		}
	};
	
})(window.jQuery || window.$);

$(document).ready(function(){
	$.feedback({
		button: '.boton-feedback',
		sendedSuccess: '<?php __e('Envío de feedback correcto'); ?>',
		sendedError: '<?php __e('Erro de envío de feedback'); ?>'
	});
});