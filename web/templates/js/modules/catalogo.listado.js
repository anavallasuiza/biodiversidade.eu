/*jslint browser:true */
/*global google, $, mapas, common, config, catalogo, alert, confirm, i18n */

if (!catalogo) {
	var catalogo = {};
}

/**
 * Species module
 */
catalogo.listado = (function () {
	
	'use strict';

	/**
	 * Public interface
	 */
	var self = {},
		listeners = {};
	
	function updateEspecies(evt, url) {
		
		var data = {},
			$buscador = $(self.elements.buscador),
			$ameaza = $(self.elements.ameaza),
            code,
            familias = [],
            xeneros = [];
		
		if (!url) {
			
			url = config.catalogo.URL_GET_ESPECIES;
			
			if ($buscador.val()) {
				data.busca = $buscador.val();
			}
			
			if ($ameaza.val() >= 0) {
				data.ameaza = $ameaza.val();
			}
            
            for (code in self.familias) {
                if (self.familias.hasOwnProperty(code)) {
                    familias.push(code);
                }
            }
            
            data.familias = familias;
            
            for (code in self.xeneros) {
                if (self.xeneros.hasOwnProperty(code)) {
                    xeneros.push(code);
                }
            }
            
            data.xeneros = xeneros;
		}
		
		$(self.elements.overlay).fadeIn(config.ANIMATION_SPEED);
		
		// Make the request
		$.ajax({
			url: url,
			type: 'get',
			dataType: 'html',
			data: data,
			success: function (data, status, xhr) {
				//loadAvistamentos.call(self, data);
				$(self.elements.listadoEspecies).find('> div').eq(0).html(data);
			},
			error: function (xhr, status, error) {
				alert(i18n.catalogo.ERROR_ESPECIES);
			},
			complete: function () {
				$(self.elements.overlay).fadeOut(config.ANIMATION_SPEED);
			}
		});
		
		return false;
	}
	
    function createRow(code, name, type) {
        
        var $li,
            $span;
        
        $li = $('<li class="selected"></li>');
        $span = $('<span class="especie" data-codigo="' + code + '" data-type="' + type + '"><i class="icon-remove"></i>' + name + '</span>');
        $span.appendTo($li);
        
        return $li;
    }
    
    
    
	function updateSeleccion() {
		
        var $listado = $(self.elements.selection + ' ul'),
            $senSeleccion = $(self.elements.senSeleccion),
            code,
            $li;
        
        $listado.html('');
		
        for (code in self.familias) {
            if (self.familias.hasOwnProperty(code)) {
                $li = createRow(code, self.familias[code], 'familias');
                $li.appendTo($listado);
            }
        }
        
        for (code in self.xeneros) {
            if (self.xeneros.hasOwnProperty(code)) {
                $li = createRow(code, self.xeneros[code], 'xeneros');
                $li.appendTo($listado);
            }
        }
		
        if ($listado.find('li').length > 0) {
            $senSeleccion.hide();
            $listado.show();
        } else {
            $senSeleccion.hide();
            $listado.show();
        }
        
        updateEspecies();
	}

	function selectXenero(evt) {
		
		var $this = $(evt.currentTarget),
			code = $this.data('codigo'),
			name = $this.data('name');
		
		self.xeneros[code] = name;
		
		updateSeleccion();
		
		return false;
	}
	
	function selectFamilia(evt) {
		
		var $this = $(evt.currentTarget),
			$familia = $this.next(),
			code = $familia.data('codigo'),
			name = $familia.data('name');
		
		
		self.familias[code] = name;
		
		updateSeleccion();
		
		return false;
	}
    
    function deselect(evt) {
        
        var $this = $(evt.currentTarget),
            $parent = $this.parent(),
            code = $parent.data('codigo'),
            type = $parent.data('type');
        
        delete self[type][code];
        updateSeleccion();
    }
	
	/**
	 * List of the elements that we are goind to use in the module
	 */
	self.elements = {
        base: 'section.content.catalogo',
		search: '.formulario-filtrar input',
		tree: '.tree',
		reino: 'span.reino',
		xenero: 'span.xeneros',
		linkFamilia: 'a.todas',
		listadoEspecies: '.listado-especies',
		buscador: '#texto-especies',
		buttonBuscador: '#texto-boton',
		pagination: 'ul.paxinacion',
		formTexto: 'form.form-especie',
		ameaza: 'select.nivel-ameaza',
		buttonAmeaza: '#boton-ameaza',
		overlay: '.especies-overlay',
        selection: '.lista-especies-seleccionadas',
        senSeleccion: '.lista-especies-seleccionadas .sen-especies',
        removeSeleccion: '.icon-remove'
	};
	
	self.searchTimer = null;
	
	self.familias = {};
	
	self.xeneros = {};
	
	/**
	 * Initialization method
	 */
	self.init = function () {
		
        if ($(self.elements.base).length) {
            $(self.elements.tree).on('click', self.elements.xenero, selectXenero);
            $(self.elements.tree).on('click', self.elements.linkFamilia, selectFamilia);
            
            $(self.elements.buttonBuscador).on('click', updateEspecies);
            $(self.elements.formTexto).on('submit', updateEspecies);
            
            $(self.elements.buttonAmeaza).on('click', updateEspecies);
            
            $(self.elements.selection).on('click', self.elements.removeSeleccion, deselect);
            
            $(document.body).on('click', self.elements.pagination + ' a', function (evt) {
                updateEspecies(evt, evt.currentTarget.href);
                return false;
            });
            
            updateEspecies();
        }
	};
	
	return self;
}());