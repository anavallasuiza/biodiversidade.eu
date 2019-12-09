

$(document).ready(function(){

	"use strict";

	$('.cambiar-opcion').on('click', function(){

		var $this = $(this);
		var $parent = $this.parent();

		var $elemento = $parent.find('.selector-especie');
		var $contenedor = $parent.find('.opcion-por-defecto');
		var $listaxeEspecie = $parent.find('.listaxe-total-especies');
		var $select = $listaxeEspecie.find('input.listaxe-especies');

		if ($contenedor.is(':visible')) {

			$elemento.attr('disabled', 'disabled');
			$select.removeAttr('disabled').select2('enable', false);

			$contenedor.hide();
			$listaxeEspecie.show();
		} else {

			$select.attr('diabled', 'disabled').select2('enable', true);
			$elemento.removeAttr('disabled');

			$contenedor.show();
			$listaxeEspecie.hide();
		}

		var texto = $this.data('text');
		$this.data('text', $this.html());
		$this.html(texto);

		return false;
	});

	$('form').on('submit', function(){

		var $this = $(this);

		var $celdas = $this.find('table tbody tr.invalid-points');

		if ($celdas.length) {
			alert('<?php __e("Detectaronse rexistros con coordenadas invalidas, debe correxilos antes de facer a importación"); ?>');
			return false;
		}
		/*
		var $celdas = $this.find('table tbody tr td.especie');

		$celdas.each(function(){
		});
		*/
	});

	$('table td.ver').on('click', function(){
		
		var $this = $(this);
		var $i = $this.find('i');
		var $next = $this.parent().next();
		
		if ($i.hasClass('icon-caret-right')) {
			$next.show();
			$i.attr('class', 'icon-caret-down');
		} else {
			$next.hide();
			$i.attr('class', 'icon-caret-right');
		}
	});
});
